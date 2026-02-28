<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private const PREVIEW_ALLOWED_EXTENSIONS = ['pdf'];

    /**
     * Display all books (Halaman Produk)
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Filter by format if requested
        if ($request->has('format') && in_array($request->format, ['ebook', 'printed', 'physical'])) {
            $query->where(function ($q) use ($request) {
                if ($request->format === 'ebook') {
                    $q->where('format', 'ebook')->orWhere('format', 'both');
                    return;
                }

                $q->whereIn('format', ['physical', 'printed', 'both']);
            });
        }

        $books = $query->latest()->paginate(12);

        return view('public.books.produk', compact('books'));
    }

    /**
     * Display E-Book collections with filter by author
     */
    public function ebooks(Request $request)
    {
        $query = Book::where(function ($q) {
            $q->where('format', 'ebook')->orWhere('format', 'both');
        })->with('authors', 'category');

        // Filter by author if selected
        if ($request->has('authors') && !empty($request->authors)) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->whereIn('authors.id', $request->authors);
            });
        }

        $books = $query->latest()->get();

        // Get all unique authors for ebooks filter
        $authors = Author::whereHas('books', function ($q) {
            $q->where(function ($q2) {
                $q2->where('format', 'ebook')->orWhere('format', 'both');
            });
        })->get();

        return view('public.books.ebooks', compact('books', 'authors'));
    }

    /**
     * Display Printed Books - Buku Fisik/Hardcover
     */
    public function printedBooks(Request $request)
    {
        $query = Book::whereIn('format', ['physical', 'printed', 'both']);

        // Sort functionality
        $sort = $request->input('sort', 'latest');

        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(12);

        return view('public.books.printed', compact('books'));
    }

    /**
     * Display the specified book detail
     */
    public function show($id)
    {
        $book = Book::with('authors', 'category')->findOrFail($id);
        $previewAvailable = $this->isEbookPreviewAvailable($book);

        // Get related books (same category or same authors)
        $relatedBooks = Book::where('id', '!=', $id)
            ->where(function ($query) use ($book) {
                // Same category
                $query->where('category_id', $book->category_id);
                // OR same authors
                if ($book->authors->count() > 0) {
                    $query->orWhereHas('authors', function ($q) use ($book) {
                        $q->whereIn('author_id', $book->authors->pluck('id'));
                    });
                }
            })
            ->with('authors', 'category')
            ->latest()
            ->limit(5)
            ->get();

        return view('public.books.detail', compact('book', 'relatedBooks', 'previewAvailable'));
    }

    /**
     * Show dedicated ebook preview page
     */
    public function preview($id)
    {
        $book = Book::with('authors', 'category')->findOrFail($id);

        if (!$this->isEbookPreviewAvailable($book)) {
            return redirect()->route('book.detail', $book->id)
                ->with('error', 'Preview ebook belum tersedia untuk buku ini.');
        }

        return view('public.books.preview', compact('book'));
    }

    /**
     * Stream ebook file for inline preview
     */
    public function previewFile($id)
    {
        $book = Book::findOrFail($id);

        if (!$this->isEbookPreviewAvailable($book)) {
            abort(404, 'Preview ebook tidak tersedia');
        }

        $ebookPath = $this->resolveEbookPath($book->ebook_file);

        if (!$ebookPath) {
            abort(404, 'File ebook tidak ditemukan');
        }

        return response()->file($ebookPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . ($book->slug ?: 'ebook-preview') . '.pdf"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    /**
     * Add selected book to cart and redirect to guest form checkout
     */
    public function startCheckout(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if (!$book->is_active) {
            return redirect()->route('book.detail', $book->id)
                ->with('error', 'Buku tidak tersedia untuk dibeli.');
        }

        if (in_array($book->format, ['physical', 'printed']) && $book->stock <= 0) {
            return redirect()->route('book.detail', $book->id)
                ->with('error', 'Stok buku fisik sedang habis.');
        }

        $cartItems = session()->get('cart', []);

        $cartItems[$book->id] = [
            'quantity' => 1,
        ];

        session()->put('cart', $cartItems);

        return redirect()->route('checkout.guest-form');
    }

    /**
     * Search books
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $format = $request->input('format');

        $query = Book::query();

        // Search by keyword
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('author', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhere('category', 'LIKE', "%{$keyword}%");
            });
        }

        // Filter by format
        if ($format && in_array($format, ['ebook', 'printed', 'physical'])) {
            $query->where(function ($q) use ($format) {
                if ($format === 'ebook') {
                    $q->where('format', 'ebook')->orWhere('format', 'both');
                    return;
                }

                $q->whereIn('format', ['physical', 'printed', 'both']);
            });
        }

        $books = $query->latest()->paginate(12);

        return view('public.books.search', compact('books', 'keyword'));
    }

    private function isEbookPreviewAvailable(Book $book): bool
    {
        if (!in_array($book->format, ['ebook', 'both'])) {
            return false;
        }

        if (empty($book->ebook_file)) {
            return false;
        }

        $extension = strtolower(pathinfo($book->ebook_file, PATHINFO_EXTENSION));
        if (!in_array($extension, self::PREVIEW_ALLOWED_EXTENSIONS)) {
            return false;
        }

        return !is_null($this->resolveEbookPath($book->ebook_file));
    }

    private function resolveEbookPath(?string $ebookFile): ?string
    {
        if (empty($ebookFile)) {
            return null;
        }

        $localRelativePath = 'ebooks/' . $ebookFile;

        if (Storage::disk('local')->exists($localRelativePath)) {
            return Storage::disk('local')->path($localRelativePath);
        }

        $legacyPath = storage_path('app/ebooks/' . $ebookFile);

        if (file_exists($legacyPath)) {
            return $legacyPath;
        }

        return null;
    }
}

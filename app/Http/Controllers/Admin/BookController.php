<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of books
     */
    public function index(Request $request)
    {
        $query = Book::with('category', 'authors');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhereHas('authors', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by format
        if ($request->filled('format')) {
            $query->where('format', $request->format);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $books = $query->paginate(15);
        $categories = Category::active()->get();

        return view('admin.books.index', [
            'books' => $books,
            'categories' => $categories,
            'search' => $request->search,
            'selectedCategory' => $request->category_id,
            'selectedFormat' => $request->format,
            'selectedStatus' => $request->status,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::active()->get();
        $authors = Author::active()->get();

        return view('admin.books.create', [
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }

    /**
     * Store book
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|unique:books',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'pages' => 'nullable|integer|min:1',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'format' => 'required|in:physical,ebook,both',
            'cover_image' => 'nullable|image|max:2048',
            'ebook_file' => 'nullable|file|mimes:pdf,epub|max:50000',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        // Handle ebook file
        if ($request->hasFile('ebook_file')) {
            $path = $request->file('ebook_file')->store('ebooks', 'local');
            $validated['ebook_file'] = basename($path);
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);

        $book = Book::create($validated);

        // Attach authors
        if (!empty($validated['authors'])) {
            $book->authors()->sync($validated['authors']);
        }

        return redirect()->route('admin.books.show', $book)->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * Show book detail
     */
    public function show(Book $book)
    {
        $book->load('category', 'authors', 'orderItems');

        return view('admin.books.show', [
            'book' => $book,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(Book $book)
    {
        $categories = Category::active()->get();
        $authors = Author::active()->get();

        return view('admin.books.edit', [
            'book' => $book,
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }

    /**
     * Update book
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'pages' => 'nullable|integer|min:1',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'format' => 'required|in:physical,ebook,both',
            'cover_image' => 'nullable|image|max:2048',
            'ebook_file' => 'nullable|file|mimes:pdf,epub|max:50000',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                \Storage::disk('public')->delete($book->cover_image);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        // Handle ebook file
        if ($request->hasFile('ebook_file')) {
            if ($book->ebook_file) {
                \Storage::disk('local')->delete('ebooks/' . $book->ebook_file);
            }
            $path = $request->file('ebook_file')->store('ebooks', 'local');
            $validated['ebook_file'] = basename($path);
        }

        // Generate slug if title changed
        if ($validated['title'] !== $book->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $book->update($validated);

        // Sync authors
        $book->authors()->sync($validated['authors']);

        return redirect()->route('admin.books.show', $book)->with('success', 'Buku berhasil diupdate');
    }

    /**
     * Delete book
     */
    public function destroy(Book $book)
    {
        // Delete files
        if ($book->cover_image) {
            \Storage::disk('public')->delete($book->cover_image);
        }
        if ($book->ebook_file) {
            \Storage::disk('local')->delete('ebooks/' . $book->ebook_file);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus');
    }

    /**
     * Bulk update books
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id',
            'action' => 'required|in:activate,deactivate,featured,unfeatured',
        ]);

        Book::whereIn('id', $validated['book_ids'])
            ->each(function ($book) use ($validated) {
                match ($validated['action']) {
                    'activate' => $book->update(['is_active' => true]),
                    'deactivate' => $book->update(['is_active' => false]),
                    'featured' => $book->update(['is_featured' => true]),
                    'unfeatured' => $book->update(['is_featured' => false]),
                };
            });

        return back()->with('success', count($validated['book_ids']) . ' buku berhasil diupdate');
    }

    /**
     * Export books to CSV
     */
    public function export(Request $request)
    {
        $books = Book::with('category', 'authors')
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->get();

        $filename = 'books_' . date('Y-m-d_His') . '.csv';
        $csv = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Headers
        fputcsv($csv, ['ID', 'Title', 'Category', 'Authors', 'ISBN', 'Price', 'Discount', 'Stock', 'Format', 'Status']);

        // Data
        foreach ($books as $book) {
            fputcsv($csv, [
                $book->id,
                $book->title,
                $book->category->name ?? '-',
                $book->authors->pluck('name')->implode(', '),
                $book->isbn,
                $book->price,
                $book->discount_price,
                $book->stock,
                $book->format,
                $book->is_active ? 'Active' : 'Inactive',
            ]);
        }

        fclose($csv);
        exit();
    }
}

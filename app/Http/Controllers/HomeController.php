<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil 6 buku terbaru untuk ditampilkan
        $books = Book::latest()->take(6)->get();

        // Data tambahan untuk homepage
        $data = [
            'books' => $books,
            'stats' => [
                'total_books' => Book::count(),
                'ebook_count' => Book::whereIn('format', ['ebook', 'both'])->count(),
                'printed_count' => Book::whereIn('format', ['physical', 'printed', 'both'])->count(),
            ]
        ];

        return view('public.home', $data);
    }
}

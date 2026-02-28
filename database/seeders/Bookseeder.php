<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'category_id' => 3,
                'title' => 'Laravel untuk Pemula',
                'slug' => 'laravel-untuk-pemula',
                'author_name' => 'Andi Prasetyo',
                'publisher' => 'Tech Publisher',
                'isbn' => '9781234567890',
                'publication_year' => 2024,
                'pages' => 350,
                'description' => 'Buku panduan lengkap untuk belajar Laravel.',
                'price' => 150000,
                'discount_price' => 120000,
                'stock' => 50,
                'format' => 'both',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($books as $data) {

            $author = Author::firstOrCreate([
                'name' => $data['author_name']
            ]);

            unset($data['author_name']);

            $book = Book::create($data);

            $book->authors()->attach($author->id);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first author and category
        $author = Author::first();
        $category = Category::first();

        if (!$author || !$category) {
            echo "Anda harus seed Authors dan Categories terlebih dahulu.\n";
            return;
        }

        // Create blog posts
        BlogPost::create([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Tips Membaca Buku Fiksi Secara Efektif',
            'slug' => 'tips-membaca-buku-fiksi-secara-efektif',
            'excerpt' => 'Pelajari cara membaca buku fiksi dengan strategi yang tepat untuk meningkatkan pemahaman dan pengalaman.',
            'content' => 'Membaca fiksi bukan hanya tentang menghabiskan halaman, tetapi tentang menghayati cerita dengan maksimal. Berikut adalah beberapa tips yang dapat membantu Anda menjadi pembaca yang lebih baik.',
            'featured_image' => 'blog/UsIGafvJi2qXTZRwkAtW5TWVZmgzrwZfBDJoJ29u.jpg',
            'is_published' => true,
            'published_at' => now()->subDays(7),
            'view_count' => 150,
        ]);

        BlogPost::create([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Penulis Terkenal Yang Harus Anda Baca',
            'slug' => 'penulis-terkenal-yang-harus-anda-baca',
            'excerpt' => 'Temukan daftar penulis-penulis terbaik dunia yang telah mengubah industri sastra.',
            'content' => 'Sastra dunia telah dipengaruhi oleh berbagai penulis yang berbakat. Mereka telah menciptakan karya-karya yang memukau dan menginspirasi jutaan pembaca di seluruh dunia.',
            'featured_image' => 'blog/VPXhf7uhpDm7CPUuYdNhYULo2zXWuMkdqIIAx5mu.jpg',
            'is_published' => true,
            'published_at' => now()->subDays(3),
            'view_count' => 200,
        ]);

        BlogPost::create([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Blog Post Tanpa Featured Image',
            'slug' => 'blog-post-tanpa-featured-image',
            'excerpt' => 'Blog post ini dibuat untuk menguji fallback placeholder ketika tidak ada featured image.',
            'content' => 'Ini adalah blog post untuk testing. Tidak ada featured image sehingga harus menampilkan placeholder.',
            'featured_image' => null,
            'is_published' => true,
            'published_at' => now()->subDays(1),
            'view_count' => 50,
        ]);

        echo "Blog posts seeding berhasil!\n";
    }
}

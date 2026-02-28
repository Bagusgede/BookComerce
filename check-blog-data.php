<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$posts = \App\Models\BlogPost::all();

echo "Blog Posts Status:\n";
echo str_repeat("-", 80) . "\n";

foreach ($posts as $post) {
    echo "ID: {$post->id}\n";
    echo "Title: {$post->title}\n";
    echo "Featured Image: " . ($post->featured_image ? $post->featured_image : "[NULL]") . "\n";
    echo "Published: " . ($post->is_published ? "Yes" : "No") . "\n";
    if ($post->featured_image) {
        $fullPath = storage_path('app/public/' . $post->featured_image);
        echo "File exists on disk: " . (file_exists($fullPath) ? "YES ✓" : "NO ✗") . "\n";
        echo "Asset URL would be: /storage/" . $post->featured_image . "\n";
    }
    echo str_repeat("-", 80) . "\n";
}

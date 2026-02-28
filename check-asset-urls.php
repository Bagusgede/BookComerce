<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$posts = \App\Models\BlogPost::where('featured_image', '!=', null)->limit(3)->get();

echo "Asset URL Generation Test:\n";
echo str_repeat("-", 80) . "\n";

foreach ($posts as $post) {
    $assetUrl = asset('storage/' . $post->featured_image);
    echo "Post: {$post->title}\n";
    echo "Featured: {$post->featured_image}\n";
    echo "Asset URL: {$assetUrl}\n";
    echo "Markdown view code: {{ asset('storage/' . \$post->featured_image) }}\n";
    echo "\n";
}

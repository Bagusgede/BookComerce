<!DOCTYPE html>
<html>

<head>
    <title>Blog Featured Images Test</title>
    <style>
        body {
            font-family: Arial;
            padding: 2rem;
            background: #f5f5f5;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .post {
            background: white;
            padding: 2rem;
            margin: 2rem 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post img {
            max-width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .placeholder {
            width: 100%;
            height: 300px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 2rem;
            margin: 1rem 0;
        }

        .code {
            background: #f0f0f0;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
        }

        .title {
            color: #333;
            margin: 1rem 0 0.5rem 0;
        }

        .meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Featured Image Rendering Test</h1>

        <?php
        require 'vendor/autoload.php';
        $app = require 'bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $posts = \App\Models\BlogPost::all();

        foreach ($posts as $post):
        ?>
            <div class="post">
                <h2 class="title">Blog Post #<?php echo $post->id; ?>: <?php echo htmlspecialchars($post->title); ?></h2>
                <div class="meta">
                    Published: <?php echo $post->is_published ? '✓ Yes' : '✗ No'; ?> |
                    Featured Image: <?php echo $post->featured_image ? htmlspecialchars($post->featured_image) : '[NONE]'; ?>
                </div>

                <!-- Render as view would -->
                <?php if ($post->featured_image): ?>
                    <img src="<?php echo asset('storage/' . $post->featured_image); ?>"
                        alt="<?php echo htmlspecialchars($post->title); ?>"
                        style="max-width: 100%; max-height: 400px; object-fit: cover; border-radius: 0.5rem; margin: 1.5rem 0;">
                <?php else: ?>
                    <div class="placeholder">📷 Tidak ada Featured Image</div>
                <?php endif; ?>

                <div class="code">
                    <strong>Blade Code:</strong><br>
                    <code>@if ($post->featured_image)<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&lt;img src="{{ asset('storage/' . $post->featured_image) }}" ... /&gt;<br>
                        @else<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&lt;div&gt;📷 Tidak ada Featured Image&lt;/div&gt;<br>
                        @endif</code>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
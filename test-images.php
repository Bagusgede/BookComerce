<!DOCTYPE html>
<html>

<head>
    <title>Featured Image Test</title>
</head>

<body>
    <h1>Featured Image Path Test</h1>

    <h2>Images at storage/app/public/blog:</h2>
    <?php
    $blogPath = __DIR__ . '/storage/app/public/blog';
    if (is_dir($blogPath)) {
        $files = array_diff(scandir($blogPath), ['.', '..']);
        echo "<ul>";
        foreach ($files as $file) {
            echo "<li>{$file}</li>";
        }
        echo "</ul>";
    }
    ?>

    <h2>Asset Path Test (relative storage):</h2>
    <p>
        Direct: <br>
        <code>/storage/blog/UsIGafvJi2qXTZRwkAtW5TWVZmgzrwZfBDJoJ29u.jpg</code>
        <br>
        <img src="/BookComerce/storage/blog/UsIGafvJi2qXTZRwkAtW5TWVZmgzrwZfBDJoJ29u.jpg"
            alt="Test Image 1"
            style="max-width: 200px; border: 1px solid #ccc;">
    </p>

    <p>
        Direct 2: <br>
        <code>/storage/blog/VPXhf7uhpDm7CPUuYdNhYULo2zXWuMkdqIIAx5mu.jpg</code>
        <br>
        <img src="/BookComerce/storage/blog/VPXhf7uhpDm7CPUuYdNhYULo2zXWuMkdqIIAx5mu.jpg"
            alt="Test Image 2"
            style="max-width: 200px; border: 1px solid #ccc;">
    </p>

    <h2>Symlink Check:</h2>
    <p>
        Public symlink to storage/app/public should be available at: <br>
        <code>http://localhost/BookComerce/storage/blog/[image-name].jpg</code>
    </p>
    <p>
        Is public/storage symlink exists?
        <?php
        $symlink = __DIR__ . '/public/storage';
        if (is_link($symlink)) {
            echo "✅ YES - Points to: " . readlink($symlink);
        } else {
            echo "❌ NO";
        }
        ?>
    </p>
</body>

</html>
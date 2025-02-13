<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Manage Pages</title>
    
    <script src="https://cdn.tiny.cloud/1/no4el2dls3ms4xzvu5gtl4ehoedvog1w20cpsblibmyif6gh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            height: 300,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            branding: false
        });
    </script>
</head>
<body>
    <h1>Manage Pages</h1>

    <?php if ($errorMessage): ?>
        <p style="color: red;"><?= $errorMessage ?></p>
    <?php endif; ?>

    <h2>Create New Page</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Page Title" required>
        <input type="text" name="slug" placeholder="Page Slug (optional)" pattern="[a-z0-9-]+" title="Only lowercase letters, numbers, and dashes are allowed.">
        <textarea id="content" name="content"></textarea>
        <button type="submit">Create Page</button>
    </form>

    <h2>Existing Pages</h2>
    <ul>
        <?php foreach ($pages as $page): ?>
            <li>
                <a href="index.php?page=view&slug=<?= htmlspecialchars($page['slug']) ?>">
                    <?= htmlspecialchars($page['title']) ?>
                </a>
                <a href="index.php?page=update-pages&id=<?= $page['id'] ?>" onclick="return confirm('Update this page?');">Modifier</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

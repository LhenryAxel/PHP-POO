<?php
require_once __DIR__ . '/../Controllers/AuthController.php';
require_once __DIR__ . '/../Models/Page.php';

use App\Controllers\AuthController;
use App\Models\Page;

$auth = new AuthController();
$pageModel = new Page();



// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title']) && isset($_POST['content'])) {
    $title = $_POST['title'];
    $slug = !empty($_POST['slug']) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $title)); // Auto-generate slug if empty
    $content = $_POST['content'];

    // Save to database
    if ($pageModel->createPage($title, $slug, $content, $_SESSION['user']['id'])) {
        echo "<p>Page saved successfully!</p>";
        header("Location: index.php?page=manage-pages");
        exit();
    } else {
        echo "<p>Error saving page.</p>";
    }
}

// Fetch existing pages
$pages = $pageModel->getAllPages();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Manage Pages</title>
    
    <!-- TinyMCE Script -->
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
                <a href="index.php?page=update-pages&id=<?= $page['id'] ?>" onclick="return confirm('update this page?');">Modifier</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

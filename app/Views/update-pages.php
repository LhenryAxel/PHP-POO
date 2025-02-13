<?php
require_once __DIR__ . '/../Controllers/PageController.php';

use App\Controllers\PageController;

$pageController = new PageController();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la page</title>
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
    <h1>Modifier la page CMS</h1>

    <form method="POST">
        <input type="text" name="title" required value="<?= htmlspecialchars($page['title']) ?>">
        <input type="text" name="slug" pattern="[a-z0-9-]+" title="Only lowercase letters, numbers, and dashes are allowed." value="<?= htmlspecialchars($page['slug']) ?>">
        <textarea id="content" name="content"><?= htmlspecialchars($page['content']) ?></textarea>
        <input type="hidden" name="id" value="<?= htmlspecialchars($page['id']) ?>">
        <button type="submit" name="submit_edit_page">Enregistrer</button>
    </form>
    <? $result ? ' ' : 'Erreur lors de la modification' ?>
    <p><a href="index.php?page=admin">Retour</a></p>
</body>
</html>

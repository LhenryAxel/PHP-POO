<?php
require_once __DIR__ . '/../Controllers/PageController.php';

use App\Controllers\PageController;

$pageController = new PageController();

$structure = $pageController->getGlobalStructure();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $header = $_POST['header'];
    $footer = $_POST['footer'];

    $pageController->updateGlobalStructure($header, $footer);
    header("Location: index.php?page=admin-structure");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la structure globale</title>
    <script src="https://cdn.tiny.cloud/1/no4el2dls3ms4xzvu5gtl4ehoedvog1w20cpsblibmyif6gh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.wysiwyg',
            height: 200,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            branding: false
        });
    </script>
</head>
<body>
    <h1>Modifier la structure globale du CMS</h1>

    <form method="POST">
        <label>Header</label>
        <textarea class="wysiwyg" name="header"><?= htmlspecialchars($structure['header']) ?></textarea>

        <label>Footer</label>
        <textarea class="wysiwyg" name="footer"><?= htmlspecialchars($structure['footer']) ?></textarea>

        <button type="submit">Enregistrer</button>
    </form>

    <p><a href="index.php?page=admin">Retour à l'admin</a></p>
</body>
</html>

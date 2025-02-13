<?php
require_once __DIR__ . '/../../core/autoload.php';



use App\Controllers\PageController;

$pageController = new PageController();
$structure = $pageController->getGlobalStructure();
$pageData = $pageController->showPage($_GET['slug'] ?? '');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?= $structure['head'] ?? '' ?>
</head>
<body>
    <?= $structure['header'] ?? '' ?>

    <h1><?= htmlspecialchars($pageData['title']) ?></h1>
    <p><?= $pageData['content'] ?></p>

    <?= $structure['footer'] ?? '' ?>
</body>
</html>

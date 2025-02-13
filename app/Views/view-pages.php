<?php
require_once __DIR__ . '/../Models/Page.php';

use App\Models\Page;
use App\Models\PageModel;

$pageModel = new PageModel();
$slug = $_GET['slug'] ?? null;
// $page = $pageModel->getPageBySlug($slug);

if (!$page) {
    echo "<h1>Page not found</h1>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page['title']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($page['title']) ?></h1>
    <div><?= $page['content'] ?></div>
</body>
</html>

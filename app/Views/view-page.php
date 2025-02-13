<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageData['title']) ?></title>
</head>
<body>
    <?= $structure['header'] ?? '<header><h1>Default Header</h1></header>'; ?>
    
    <h1><?= htmlspecialchars($pageData['title']) ?></h1>
    <p><?= $pageData['content'] ?></p>

    <a href="index.php">Retour</a>

    <?= $structure['footer'] ?? '<footer><p>Default Footer</p></footer>'; ?>
</body>
</html>

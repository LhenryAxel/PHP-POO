<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageData['title']) ?></title>
</head>
<body>

    <header>
        <?= isset($structure['header']) ? $structure['header'] : '<h2>Default Header</h2>' ?>
    </header>

    <h1><?= htmlspecialchars($pageData['title']) ?></h1>
    <div><?= $pageData['content'] ?></div>

    <footer>
        <?= isset($structure['footer']) ? $structure['footer'] : '<p>Default Footer</p>' ?>
    </footer>

    <a href="index.php">Retour</a>

</body>
</html>

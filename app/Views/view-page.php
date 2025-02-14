<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?= htmlspecialchars($pageData['title']) ?></title>
    <meta charset="UTF-8"></head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $structure['head'] ?? ''; ?>
</head>
<body>
    <?= $structure['header'] ?? '<footer><p>Default Footer</p></footer>'; ?>

    <div class="main">
        <h1  class="title"><?= htmlspecialchars($pageData['title']) ?></h1>
        <p><?= $pageData['content'] ?></p>

    </div>

    <?= $structure['footer'] ?? '<footer><p>Default Footer</p></footer>'; ?>

</body>
</html>

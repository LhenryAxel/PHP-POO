<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageData['title']) ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        .container {
            background: white;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        /* Header & Footer */
        header, footer {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        footer {
            margin-top: 20px;
        }

        .back-btn {
            display: inline-block;
            margin: 20px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #0056b3;
        }

    </style>
</head>
<body>

    <?= $structure['header'] ?? '<header><h1>Default Header</h1></header>'; ?>

    <div class="container">
        <h1><?= htmlspecialchars($pageData['title']) ?></h1>
        <p><?= nl2br(htmlspecialchars($pageData['content'])) ?></p>

        <a href="index.php" class="back-btn">Retour</a>
    </div>

    <?= $structure['footer'] ?? '<footer><p>Default Footer</p></footer>'; ?>

</body>
</html>

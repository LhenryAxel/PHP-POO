<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des pages</title>

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

        .button-container {
            margin-bottom: 15px;
        }

        a.button {
            text-decoration: none;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
            display: inline-block;
            margin: 5px;
        }

        a.button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            max-width: 900px;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #a71d2a;
        }

    </style>

</head>
<body>

    <h1>Liste des pages</h1>

    <div class="button-container">
        <a href="index.php?page=admin" class="button">Retour à l'admin</a>
        <a href="index.php" class="button">Retour</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Url</th>
                <th>Contenu</th>
                <th>Utilisateur</th>
                <th>Date de création</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pages as $page): ?>
                <tr>
                    <td><?= htmlspecialchars($page->GetId()) ?></td>
                    <td><?= htmlspecialchars($page->GetTitle()) ?></td>
                    
                    <td>
                        <a href="index.php?page=view&slug=<?= urlencode($page->GetSlug()) ?>">
                            <?= htmlspecialchars($page->GetSlug()) ?>
                        </a>
                    </td>
                    
                    <td><?= htmlspecialchars(strip_tags($page->GetContent())) ?></td>
                    <td><?= htmlspecialchars($page->GetCreatedByUser()->GetEmail()) ?></td>
                    <td><?= htmlspecialchars($page->GetCreatedAtAsString()) ?></td>
                    
                    <td>
                        <form method="POST" action="index.php?page=delete-page">
                            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($page->GetId()) ?>"/>
                            <button type="submit" name="delete_btn">Supprimer</button>
                        </form> 
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>

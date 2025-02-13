<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des pages</title>
</head>
<body>
    <h1>Liste des pages</h1>
    <a href="index.php">Retour</a>
    <br/>
    <table border="1">
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
                    <td><?= htmlspecialchars($page['id']) ?></td>
                    <td><?= htmlspecialchars($page['title']) ?></td>
                    
                    <td>
                        <a href="index.php?page=view&slug=<?= urlencode($page['slug']) ?>">
                            <?= htmlspecialchars($page['slug']) ?>
                        </a>
                    </td>
                    
                    <td><?= htmlspecialchars(strip_tags($page['content'])) ?></td>
                    <td><?= htmlspecialchars($page['email']) ?></td>
                    <td><?= htmlspecialchars($page['created_at']) ?></td>
                    
                    <td>
                        <form method="POST" action="index.php?page=delete-page">
                            <input type="hidden" name="delete_id" id="delete_id" value="<?= htmlspecialchars($page['id']) ?>"/>
                            <button type="submit" id='delete_btn' name="delete_btn">Supprimer</button>
                        </form> 
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

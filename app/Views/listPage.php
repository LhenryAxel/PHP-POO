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
                <th>Contenue</th>
                <th>Utilisateur</th>
                <th>Date de création</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pages as $page): ?>
                <tr>
                    <td><?= $page['id'] ?></td>
                    <td><?= $page['title'] ?></td>
                    <td><?= $page['slug'] ?></td>
                    <td><?= $page['content'] ?></td>
                    <td><?= $page['email'] ?></td>
                    <td><?= $page['created_at'] ?></td>
                    <td>
                        <form method="POST" action="index.php?page=delete-page">
                            <input type="hidden" name="delete_id" id="delete_id" value="<?= $page['id'] ?>"/>
                            <button type="submit" id='delete_btn' name="delete_btn">Supprimer</button>
                        </form> 
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

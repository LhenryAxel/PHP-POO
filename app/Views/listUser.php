<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
</head>
<body>
    <h1>Liste des utilisateurs</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date de création</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>utilisateur1@example.com</td>
                <td>Admin</td>
                <td>2025-01-01</td>
                <td>
                    <button>Modifier</button>
                    <button>Supprimer</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>utilisateur2@example.com</td>
                <td>Utilisateur</td>
                <td>2025-02-01</td>
                <td>
                    <button>Modifier</button>
                    <button>Supprimer</button>
                </td>
            </tr>
            <!-- Ajoutez d'autres utilisateurs ici -->
        </tbody>
    </table>
</body>
</html>

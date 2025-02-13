<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        p {
            text-align: center;
            font-size: 16px;
            color: #555;
        }

        ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }

        ul li {
            display: inline-block;
            margin: 10px;
        }

        ul li a {
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
        }

        ul li a:hover {
            background-color: #0056b3;
        }

        .logout {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .logout:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>

    <div class="container">
        <h1>Panneau d'administration</h1>

        <?php if (isset($_SESSION['user'])): ?>
            <p>Bienvenue Admin, <strong><?= htmlspecialchars($_SESSION['user']['email']); ?></strong></p>
            <a href="logout.php" class="logout">Déconnexion</a>

            <ul>
                <li><a href="index.php?page=list-users">Gérer les utilisateurs</a></li>
                <li><a href="index.php?page=list-pages">Voir la listes des pages</a></li>
                <li><a href="index.php?page=admin-structure">Modifier la structure des pages</a></li>
                <li><a href="index.php?page=home">Accueil</a></li>
            </ul>
        <?php else: ?>
            <p>Accès refusé. <a href="index.php?page=login">Connexion</a></p>
        <?php endif; ?>
    </div>

</body>
</html>

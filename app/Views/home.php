<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            padding: 10px 20px;
            border-color: #007bff;
            color:rgb(0, 0, 0);
            border-radius: 5px;
            transition: 0.3s;
            font-size: 16px;
            display: inline-block;
        }

        .create_btn {
            background-color: #007bff;
            color: white;
        }

        a:hover {
            background-color: #0056b3;
            color: white;
        }

        .logout {
            display: block;
            color: white;
            margin-top: 20px;
            background-color: #dc3545;
        }

        .logout:hover {
            background-color: #a71d2a;
        }
        .admin-btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            margin-top: 15px;
            transition: 0.3s;
        }

        .admin-btn:hover {
            background-color: #1e7e34;
        }

        .list-pages ul {
            list-style: none;
            padding: 0;
        }

        .list-pages li {
            margin: 10px 0;
        }

        .list-pages a {
            display: block;
            padding: 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }

        .list-pages a:hover {
            background-color: #0056b3;
        }

    </style>

</head>
<body>

    <div class="container">
        <h1>Bienvenue sur votre espace</h1>
        <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['user']['email']); ?></strong></p>
        <a class="create_btn" href="index.php?page=manage-pages">Créer les pages</a>

        <h2> Liste des pages </h2>
        <ul>
        <?php foreach($pages as $page): ?>
            <li>
                <a href="index.php?page=view&slug=<?= urlencode($page->GetSlug()) ?>">
                            <?= htmlspecialchars($page->GetTitle()) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>


        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == "admin") { echo "<a href=\"index.php?page=admin\" class=\"admin-btn\">Retour au Panneau d'Admin</a>"; } ?>

        <a href="index.php?page=logout" class="logout">Déconnexion</a>
    </div>

</body>
</html>

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
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
            font-size: 16px;
            display: inline-block;
        }

        a:hover {
            background-color: #0056b3;
        }

        .logout {
            display: block;
            margin-top: 20px;
            background-color: #dc3545;
        }

        .logout:hover {
            background-color: #a71d2a;
        }

    </style>

</head>
<body>

    <div class="container">
        <h1>Bienvenue sur votre espace</h1>
        <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['user']['email']); ?></strong></p>

        <ul>
            <li><a href="index.php?page=manage-pages">Créer les pages</a></li>

            <li><a href="index.php?page=home">Accueil</a></li>
        </ul>

        <a href="index.php?page=logout" class="logout">Déconnexion</a>

        <p>Vous êtes connecté en tant qu'utilisateur standard.</p>
    </div>

</body>
</html>

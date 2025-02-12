<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
</head>
<body>
    <h1>Bienvenue sur votre espace</h1>
    
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['user']['email']); ?> | 
        <a href="index.php?page=logout">Déconnexion</a>
    </p>

    <p>Vous êtes connecté en tant qu'utilisateur standard.</p>
</body>
</html>

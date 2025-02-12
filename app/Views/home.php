<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
</head>
<body>
    <h1>Bienvenue sur votre espace</h1>
    
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['user']['email']); ?> | 
        
    </p>

    <ul>
            <li><a href="index.php?page=manage-pages">Gérer les pages</a></li>
            <li><a href="index.php?page=structure-page">Modifier la structure des pages</a></li>
            <li><a href="index.php?page=home">Accueil</a></li>
    </ul>

        <a href="index.php?page=logout">Déconnexion</a>
    <p>Vous êtes connecté en tant qu'utilisateur standard.</p>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin</title>
</head>
<body>
    <h1>Panneau d'administration</h1>
    
    <?php if (isset($_SESSION['user'])): ?>
        <p>Bienvenue Admin, <?= htmlspecialchars($_SESSION['user']['email']); ?> | 
        <a href="logout.php">Déconnexion</a>
        <ul>
            <li><a href="index.php?page=list-users">Gérer les utilisateurs</a></li>
            <li><a href="index.php?page=list-pages">Gérer les pages</a></li>
            <li><a href="index.php?page=structure-page">Modifier la structure des pages</a></li>
            <li><a href="index.php?page=home">Accueil</a></li>
        </ul>
    <?php else: ?>
        <p>Accès refusé. <a href="index.php?page=login">Connexion</a></p>
    <?php endif; ?>
</body>
</html>

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
            <li><a href="index.php?page=manage-users">Gérer les utilisateurs</a></li>
            <li><a href="index.php?page=manage-pages">Gérer les pages</a></li>
        </ul>
    <?php else: ?>
        <p>Accès refusé. <a href="index.php?page=login">Connexion</a></p>
    <?php endif; ?>
</body>
</html>

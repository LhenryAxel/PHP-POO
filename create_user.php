<?php
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/app/Models/User.php';

use Core\Database;
use App\Models\User;

echo "Email: ";
$email = trim(fgets(STDIN));

echo "Mot de passe: ";
$password = trim(fgets(STDIN));

echo "Rôle (user/admin): ";
$role = trim(fgets(STDIN));

if (!in_array($role, ['user', 'admin'])) {
    die("Rôle invalide. Utilisez 'user' ou 'admin'.\n");
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    $db = Database::getInstance();
    $stmt = $db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$email, $hashedPassword, $role]);

    echo "Utilisateur ajouté avec succès : $email ($role)\n";
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage() . "\n");
}

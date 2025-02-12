<?php
namespace App\Models;

require_once __DIR__ . '/../../core/Database.php';

use PDO;
use Core\Database;


class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getUserByEmail(string $email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser(string $email, string $password, string $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $hashedPassword, $role]);
    }
}

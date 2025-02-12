<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($email, $password): bool {
        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            $this->redirectUser();
            return true;
        }
        return false;
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function isAuthenticated(): bool {
        return isset($_SESSION['user']);
    }

    public function isAdmin(): bool {
        return $this->isAuthenticated() && $_SESSION['user']['role'] === 'admin';
    }

    public function getUser() {
        return $_SESSION['user'] ?? null;
    }

    public function redirectUser() {
        if ($this->isAdmin()) {
            header("Location: index.php?page=admin");
        } else {
            header("Location: index.php?page=home");
        }
        exit();
    }
}

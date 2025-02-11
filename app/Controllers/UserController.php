<?php
namespace App\Controllers;

use App\Models\User;

class UserController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login($email, $password) {
        $user = $this->userModel->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            return "Connexion réussie !";
        }
        return "Échec de la connexion.";
    }

    public function logout() {
        session_destroy();
        return "Déconnexion réussie.";
    }
}

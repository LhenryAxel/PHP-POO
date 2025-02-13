<?php
namespace App\Controllers;

use App\Models\UserModel;

class UserController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
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

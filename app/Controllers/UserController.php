<?php
namespace App\Controllers;

use App\Models\UserModel;

class UserController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = UserModel::GetInstance();
    }

    public function login($email, $password) {
        $user = $this->userModel->getUserByEmail($email);

        if ($user != null && password_verify($password, $user->GetPassword())) {
            $_SESSION['user'] = [
                'id' => $user->GetId(),
                'email' => $user->GetEmail(),
                'role' => $user->GetRole()
            ];
            return "Connexion réussie !";
        }
        return "Échec de la connexion.";
    }

    public function logout() {
        session_destroy();
        return "Déconnexion réussie.";
    }
}

<?php
namespace Core;

use App\Controllers\UserController;
use App\Controllers\PageController;

class Router {
    public function handleRequest() {
        session_start();
        $page = $_GET['page'] ?? 'home';

        switch ($page) {
            case 'login':
                $controller = new UserController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo $controller->login($_POST['email'], $_POST['password']);
                } else {
                    require __DIR__ . '/../app/Views/login.php';
                }
                break;

            case 'home':
            default:
                require __DIR__ . '/../app/Views/home.php';
                break;
        }
    }
}

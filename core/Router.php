<?php
namespace Core;

use App\Controllers\UserController;
use App\Controllers\PageController;
use App\Controllers\AuthController;

class Router {
    public function handleRequest() { 
        $page = $_GET['page'] ?? 'home';

        switch ($page) {
            case 'login':
                $controller = new AuthController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo $controller->login($_POST['email'], $_POST['password']);
                } else {
                    require __DIR__ . '/../app/Views/login.php';
                }
                break;
            
            case 'logout':
                $controller = new AuthController();
                echo $controller->logout();
                header("Location: index.php");
                exit();            

            case 'home':
            default:
                require __DIR__ . '/../app/Views/home.php';
                break;
        }
    }
}

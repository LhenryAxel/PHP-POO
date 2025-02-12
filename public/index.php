<?php
require_once __DIR__ . '/../core/autoload.php';

use Core\Router;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();
$auth = new AuthController();
$page = $_GET['page'] ?? 'guest';
if ($auth->isAuthenticated()) {
    $user = $auth->getUser();

    if ($user['role'] === 'admin') {
        $admin = new AdminController();
        
        echo $page;
        switch($page){
            case 'manage-users':
                $admin->listUser();
                break;
            case 'delete-user':
                $admin->deleteUser();
            case 'login':
                require_once __DIR__ . '/../public/login.php';
                break;
            case 'logout':
                $auth->logout();
                break;
            case 'home':
                require_once __DIR__ . '/../app/Views/home.php';
                exit();
            default:
                require_once __DIR__ . '/../app/Views/admin.php';
                break;
        }
        exit();
    }
    else if ($user['role'] === 'user') {
        switch ($page) {
            case 'login':
                require_once __DIR__ . '/../public/login.php';
                break;
            case 'logout':
                $auth->logout();
                break;
            default:
                require_once __DIR__ . '/../app/Views/home.php';
                exit();
        }
        exit();
    }
    else{
        require_once __DIR__ . '/../app/Views/guest.php';
    }
}
else{
    switch ($page) {
        case 'login':
            require_once __DIR__ . '/../public/login.php';
            break;
        case 'logout':
            $auth->logout();
            break;
        default:
            require_once __DIR__ . '/../app/Views/guest.php';
            exit();
    }
    exit();
}
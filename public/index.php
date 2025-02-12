<?php
require_once __DIR__ . '/../core/autoload.php';

use Core\Router;
use App\Controllers\AuthController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();
$auth = new AuthController();
$page = $_GET['page'] ?? 'guest';

if ($auth->isAuthenticated()) {
    $user = $auth->getUser();

    if ($user['role'] === 'admin') {
        switch ($page) {
            case 'manage-users':
                require_once __DIR__ . '/../app/Views/listUser.php';
                break;
            case 'manage-pages': // ✅ Handle manage pages
                require_once __DIR__ . '/../app/Views/manage-pages.php';
                break;
            case 'login':
                require_once __DIR__ . '/../public/login.php';
                break;
            case 'logout':
                $auth->logout();
                break;
            case 'home':
                require_once __DIR__ . '/../app/Views/home.php';
                break;
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
            case 'manage-pages': // ❌ Restrict access to manage pages for users
                echo "<h3>Access denied! You must be an admin to view this page.</h3>";
                break;
            default:
                require_once __DIR__ . '/../app/Views/home.php';
                break;
        }
        exit();
    } 
    else {
        require_once __DIR__ . '/../app/Views/guest.php';
        exit();
    }
} 
else { // 🔹 Handle unauthenticated users
    switch ($page) {
        case 'login':
            require_once __DIR__ . '/../public/login.php';
            break;
        case 'logout':
            $auth->logout();
            break;
        case 'manage-pages': // ❌ Block guests from accessing manage pages
            echo "<h3>Access denied! Please log in as admin.</h3>";
            break;
        default:
            require_once __DIR__ . '/../app/Views/guest.php';
            break;
    }
    exit();
}

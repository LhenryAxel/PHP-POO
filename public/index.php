<?php
require_once __DIR__ . '/../core/autoload.php';

use Core\Router;
use App\Controllers\AuthController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();
$auth = new AuthController();

if ($auth->isAuthenticated()) {
    $user = $auth->getUser();

    if ($user['role'] === 'admin') {
        require_once __DIR__ . '/../app/Views/admin.php';
        exit();
    }

    require_once __DIR__ . '/../app/Views/home.php';
    exit();
}

$page = $_GET['page'] ?? 'guest';

switch ($page) {
    case 'login':
        require_once __DIR__ . '/../public/login.php';
        break;
    case 'logout':
        $auth->logout();
        break;
    default:
        require_once __DIR__ . '/../app/Views/guest.php';
        break;
}
exit();

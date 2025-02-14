<?php
require_once __DIR__ . '/../core/autoload.php';

use Core\Router;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\PageController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();
$auth = new AuthController();
$pageController = new PageController();
$page = $_GET['page'] ?? 'home';

// Check if user is authenticated
if ($auth->isAuthenticated()) {
    $user = $auth->getUser();
    
    if ($user['role'] === 'admin') {
        $admin = new AdminController();
        switch ($page) {
            case 'list-users':
                $admin->listUser();
                break;
            case 'list-pages':
                $admin->listPage();
                break;
            case 'delete-user':
                $admin->deleteUser();
                break;
            case 'delete-page':
                $admin->deletePage();
                break;
            case 'manage-pages':
                $errorMessage = $pageController->handleCreatePage();
                require_once __DIR__ . '/../app/Views/manage-pages.php';
                break;
            case 'admin-structure':
                $errorMessage = $pageController->handleStructureUpdate();
                $structure = $pageController->getGlobalStructure();
                require_once __DIR__ . '/../app/Views/admin-structure.php';
                break;
            case 'view':
                $slug = $_GET['slug'] ?? '';
                $pageData = $pageController->showPage($slug);
                $structure = $pageController->getGlobalStructure();
                require_once __DIR__ . '/../app/Views/view-page.php';
                break;      
            case 'update-pages':
                $pageController = new PageController();
                $pageController->editPage();     
                break;
            case 'logout':
                $auth->logout();
                header("Location: index.php");
                exit();
            case 'home':
                $pageController->viewListPage();
                break;
            default:
                require_once __DIR__ . '/../app/Views/admin.php';
                break;
        }
        exit();
    } 
    
    else if ($user['role'] === 'user') {
        switch ($page) {
            case 'manage-pages':
                $errorMessage = $pageController->handleCreatePage();
                $pages = $pageController->listPages();
                require_once __DIR__ . '/../app/Views/manage-pages.php';
                break;
            case 'update-pages':
                $pageController = new PageController();
                $pageController->editPage();
                break;
            case 'view':
                $slug = $_GET['slug'] ?? '';
                $pageData = $pageController->showPage($slug);
                $structure = $pageController->getGlobalStructure();
                require_once __DIR__ . '/../app/Views/view-page.php';
                break;
            case 'logout':
                $auth->logout();
                header("Location: index.php");
                exit();
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

else { 
    switch ($page) {
        case 'login':
            require_once __DIR__ . '/../public/login.php';
            break;
        case 'logout':
            $auth->logout();
            header("Location: index.php");
            exit();
        default:
            require_once __DIR__ . '/../app/Views/guest.php';
            break;
    }
    exit();
}

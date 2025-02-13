<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Page;

class AdminController {
    private User $userModel;
    private Page $pageModel;

    public function __construct() {
        $this->userModel = new User();
        $this->pageModel = new Page();
    }

    public function listUser() {
        $users = $this->userModel->getUsers();
        require_once __DIR__ . '/../Views/listUser.php';
        exit();
    }

    public function deleteUser(){
        if(isset($_POST["delete_btn"])){
            $result = $this->pageModel->changePageOwner($_POST["delete_id"], $_SESSION['user']['id']);
            $result = $this->userModel->deleteUser($_POST["delete_id"]);
            $this->listUser();
        }
        else{
            echo "Erreur lors de la suppréssion";
        }
    }

    public function listPage() {
        $pages = $this->pageModel->getPagesWithUsersMail();
        require_once __DIR__ . '/../Views/listPage.php';
        exit();
    }

    public function deletePage(){
        if(isset($_POST["delete_btn"])){
            $result = $this->pageModel->deletePage($_POST["delete_id"]);
            $this->listPage();
        }
        else{
            echo "Erreur lors de la suppréssion";
        }
    }
}

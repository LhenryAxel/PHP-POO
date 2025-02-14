<?php
namespace App\Controllers;

use App\Models\PageModel;
use App\Models\UserModel;

class AdminController {
    private UserModel $userModel;
    private PageModel $pageModel;

    public function __construct() {
        $this->userModel = UserModel::GetInstance();
        $this->pageModel = PageModel::GetInstance();
    }

    public function listUser() {
        $users = $this->userModel->GetAll();
        require_once __DIR__ . '/../Views/listUser.php';
        exit();
    }

    public function deleteUser(){
        if(isset($_POST["delete_btn"])){
            $result = $this->pageModel->ChangeOwner($_POST["delete_id"], $_SESSION['user']['id']);
            if($result){
            }
            $result = $this->userModel->Delete($_POST["delete_id"]);
            if($result){
            }
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

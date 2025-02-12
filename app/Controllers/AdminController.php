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
    }

    public function deleteUser(){
        if($_POST["delete_btn"]){
            $result = $this->userModel->deleteUser($_POST["delete_id"]);
            require_once __DIR__ . '/../Views/listUser.php';
        }
        else{
            echo "HEIN?";
        }
    }


}

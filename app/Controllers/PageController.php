<?php
namespace App\Controllers;

use App\Models\Page;

class PageController {
    private Page $pageModel;

    public function __construct() {
        $this->pageModel = new Page();
    }

    public function showPage($slug) {
        return $this->pageModel->getPageBySlug($slug);
    }

    public function createPage($title, $slug, $content, $userId) {
        return $this->pageModel->createPage($title, $slug, $content, $userId);
    }

    public function editPage(){
        $result = true;
        if (isset($_POST['submit_edit_page'])) {
            $result = $this->pageModel->updatePage($_POST['title'], $_POST['slug'], $_POST['content'], $_POST['id']);
        }
        if (isset($_GET['slug']) || isset($_POST['slug'])) {
            $page = $this->pageModel->getPageBySlug($_GET['slug']);
            require_once __DIR__ . '/../Views/update-pages.php';
            exit();
        } else {
            echo "marche po";
        }
    }

    public function getGlobalStructure() {
        return $this->pageModel->getGlobalStructure();
    }
    
    public function updateGlobalStructure($header, $footer) {
        return $this->pageModel->updateGlobalStructure($header, $footer);
    } 
}

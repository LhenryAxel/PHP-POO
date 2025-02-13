<?php
namespace App\Controllers;

use App\Models\Page;

class PageController {
    private Page $pageModel;

    public function __construct() {
        $this->pageModel = new Page();
    }

    public function getGlobalStructure() {
        return $this->pageModel->getGlobalStructure();
    }
    
    public function updateGlobalStructure($header, $footer) {
        return $this->pageModel->updateGlobalStructure($header, $footer);
    } 

    public function showPage($slug) {
        return $this->pageModel->getPageBySlug($slug);
    }  

    public function handleViewPage() { 
        $slug = $_GET['slug'] ?? '';

        if (!$slug) {
            echo "No page specified.";
            return;
        }

        $pageData = $this->showPage($slug);
        $structure = $this->getGlobalStructure();

        require_once __DIR__ . '/../Views/view-page.php';
    }

    public function handleStructureUpdate() {
        if (!isset($_POST['head']) || !isset($_POST['header']) || !isset($_POST['footer'])) {
            return;
        }
    
        $head = $_POST['head'];
        $header = $_POST['header'];
        $footer = $_POST['footer'];
    
        if ($this->pageModel->updateGlobalStructure($head, $header, $footer)) {
            header("Location: index.php?page=admin-structure&success=1");
            exit();
        } else {
            header("Location: index.php?page=admin-structure&error=1");
            exit();
        }
    } 
    
    public function listPages() {
        return $this->pageModel->getAllPages();
    }

    public function createPage($title, $slug, $content, $userId) {
        return $this->pageModel->createPage($title, $slug, $content, $userId);
    }

    public function handleCreatePage() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title']) && isset($_POST['content'])) {
            $title = $_POST['title'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $title)); // Auto-generate slug if empty
            $content = $_POST['content'];
            $userId = $_SESSION['user']['id'];

            if ($this->createPage($title, $slug, $content, $userId)) {
                header("Location: index.php?page=manage-pages");
                exit();
            } else {
                return "Error saving page.";
            }
        }
        return null;
    }  
}

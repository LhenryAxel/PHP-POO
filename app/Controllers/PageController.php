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
}

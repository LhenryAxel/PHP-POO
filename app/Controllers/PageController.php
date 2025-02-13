<?php
namespace App\Controllers;

use App\Models\PageModel;
use App\Models\Objects\Page;

class PageController {
	private PageModel $pageModel;

	public function __construct() {
		$this->pageModel = new PageModel();
	}

	public function showPage($slug) {
		// return $this->pageModel->getPageBySlug($slug);
	}

	public function createPage($title, $slug, $content, $userId) {
		$this->pageModel->Insert(
			new Page(
				title: $title,
				slug: $slug,
				content: $content,
				created_by: $userId
			)
		);
		return ;
	}
}

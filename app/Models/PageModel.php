<?php
namespace App\Models;

use Core\QueryParam;
use Core\ListQueryParam;
use PDO;
use Exception;
use App\Models\Model;
use App\Models\Lists\ListPage;
use App\Models\Objects\Page;

final class PageModel extends Model {
	public function getAllPages() {
		$stmt = $this->db->query("SELECT * FROM pages");
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function createPage($title, $slug, $content, $userId) {
		$stmt = $this->db->prepare("
				INSERT INTO pages (title, slug, content, created_by) 
				VALUES (:title, :slug, :content, :userId)
				");
		return $stmt->execute([
			'title' => $title,
			'slug' => $slug,
			'content' => $content,
			'userId' => $userId
		]);
	}

	public function GetAll(): ListPage {
		throw new Exception("Not Implemented Yet");
	}

	public function GetById(): ListPage {
		throw new Exception("Not Implemented Yet");
	}

	public function Update(): ListPage {
		throw new Exception("Not Implemented Yet");
	}

	public function Insert(Page $Page): int {
		$result = $this->db->InsertOne(
			"INSERT INTO pages 
				(id, title, slug, content, created_by, created_at) 
				VALUES (:id, :title, :slug, :content, :userId, NOW())
			",
			new ListQueryParam(
				new QueryParam(":id", $Page->GetId()),
				new QueryParam(":title", $Page->GetTitle(), PDO::PARAM_STR),
				new QueryParam(":slug", $Page->GetSlug(), PDO::PARAM_STR),
				new QueryParam(":content", $Page->GetContent(), PDO::PARAM_STR),
				new QueryParam(":userId", $Page->GetCreatedBy(), PDO::PARAM_INT),
			)
		);

		$Page->SetCreatedAt(date_create());

		return $result;
	}
}
<?php
namespace App\Models;

use App\Models\Lists\ListPage;
use App\Models\Objects\Page;
use Core\Database;
use Core\Query\ListQueryParam;
use Core\Query\QueryParam;
use Exception;
use PDO;
use Traits\Singleton;

class PageModel extends Model {
	use Singleton;

	public function getAllPages(): ListPage|false {
		try {
			return ListPage::NewList(
				data: $this->db->getList(
					"SELECT * FROM pages"
				),
			);
		} catch (Exception $e) {
			return false;
		}
	}

	public function getPagesWithUsersMail(): ListPage|false {
		try {
			return ListPage::NewList(
				data: $this->db->getList(
					"SELECT p.* FROM pages as p INNER JOIN users AS u ON p.created_by = u.id"
				),
				associateReference: true
			);
		} catch (Exception $e) {
			return false;
		}
	}

	public function createPage($title, $slug, $content, $userId): int|false {
		try {
			return $this->db->InsertOne(
				query: "INSERT INTO pages (title, slug, content, created_by) VALUES (:title, :slug, :content, :userId)",
				QueryParams: new ListQueryParam(
					new QueryParam('title', $title, PDO::PARAM_STR),
					new QueryParam(':slug', $slug, PDO::PARAM_STR),
					new QueryParam(':content', $content, PDO::PARAM_STR),
					new QueryParam(':userId', $userId, PDO::PARAM_INT),
				)
			);
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function UpdateById(int $id, string $title, string $slug, string $content): bool {
		try {
			return $this->db->Update(
				query: "UPDATE pages SET title = :title, slug = :slug, content = :content WHERE id = :id",
				QueryParams: new ListQueryParam(
					new QueryParam('title', $title, PDO::PARAM_STR),
					new QueryParam(':slug', $slug, PDO::PARAM_STR),
					new QueryParam(':content', $content, PDO::PARAM_STR),
					new QueryParam(':id', $id, PDO::PARAM_INT),
				)
			);
		} catch (Exception $e) {
			return false;
		}
	}

	public function ChangeOwner($oldUserId, $newUserId): bool {
		try {
			return $this->db->Update(
				query: "UPDATE pages SET created_by = :newUserId WHERE created_by = :oldUserId",
				QueryParams: new ListQueryParam(
					new QueryParam('newUserId', $newUserId, PDO::PARAM_STR),
					new QueryParam(':oldUserId', $oldUserId, PDO::PARAM_STR),
				)
			);
		} catch (Exception $e) {
			return false;
		}
	}

	public function deletePage($id){
		$stmt = $this->db->prepare("DELETE FROM pages WHERE id = ?");
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getGlobalStructure() {
		$stmt = $this->db->query("SELECT head, header, footer FROM structure LIMIT 1");
		return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['head' => '', 'header' => '', 'footer' => ''];
	}
	
	public function updateGlobalStructure($head, $header, $footer) {
		$stmt = $this->db->prepare("UPDATE structure SET head = ?, header = ?, footer = ? LIMIT 1");
		return $stmt->execute([$head, $header, $footer]);
	}    

	public function handleViewPage() {
		$slug = $_GET['slug'] ?? '';

		if (!$slug) {
			echo "No page specified.";
			return;
		}

		$pageData = $this->getPageBySlug($slug);
		$structure = $this->getGlobalStructure();

		require_once __DIR__ . '/../Views/view-page.php';
	}

	public function getPageBySlug($slug) {
		$stmt = $this->db->prepare("SELECT * FROM pages WHERE slug = :slug");
		$stmt->execute(['slug' => $slug]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function getPageHistory($pageId) {
        $stmt = $this->db->prepare("
            SELECT 
                u_creator.email AS creator_email,
                h_creator.action_date AS created_at,
                u_modifier.email AS last_modifier_email,
                h_modifier.action_date AS last_modified_at
            FROM pages p
            LEFT JOIN history h_creator ON h_creator.page_id = p.id AND h_creator.action = 'created'
            LEFT JOIN users u_creator ON u_creator.id = h_creator.user_id
            LEFT JOIN history h_modifier ON h_modifier.page_id = p.id AND h_modifier.action = 'updated'
            LEFT JOIN users u_modifier ON u_modifier.id = h_modifier.user_id
            WHERE p.id = :page_id
            ORDER BY h_modifier.action_date DESC
            LIMIT 1
        ");
        $stmt->execute(['page_id' => $pageId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    

}

<?php
namespace App\Models;

use PDO;
use Core\Database;

class Page {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getPageBySlug(string $slug) {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPage(string $title, string $slug, string $content, int $userId) {
        $stmt = $this->db->prepare("INSERT INTO pages (title, slug, content, created_by) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $slug, $content, $userId]);
    }
}

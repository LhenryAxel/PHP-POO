<?php
namespace App\Models;

use Core\Database;
use PDO;

class Page {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllPages() {
        $stmt = $this->db->query("SELECT * FROM pages");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPage($title, $slug, $content, $userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO pages (title, slug, content, created_by) VALUES (:title, :slug, :content, :userId)");
        return $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'userId' => $userId
        ]);
    }    
}

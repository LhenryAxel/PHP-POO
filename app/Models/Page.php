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

    public function getPagesWithUsersMail(){
        $stmt = $this->db->query("SELECT p.*, u.email FROM pages as p INNER JOIN users AS u ON p.created_by = u.id;");
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
    
    public function updatePage($title, $slug, $content, $userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE pages SET title = :title, slug = :slug, content = :content WHERE created_by = :userId");
        return $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'userId' => $userId
        ]);
    }

    public function changePageOwner($oldUserId, $newUserId){
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE pages SET created_by = :newUserId WHERE created_by = :oldUserId");
        return $stmt->execute([
            'newUserId' => $newUserId,
            'oldUserId' => $oldUserId,
        ]);
    }

    public function deletePage($id){
        $stmt = $this->db->prepare("DELETE FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

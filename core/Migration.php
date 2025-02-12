<?php
namespace Core;

use PDO;
use Core\Database;
use PDOException;

class Migration {
    private static array $migrations = [
        "001_create_users_table" => "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('user', 'admin') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;",
        
        "002_create_pages_table" => "CREATE TABLE IF NOT EXISTS pages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            content TEXT NOT NULL,
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;"
    ];

    private static string $migrationTable = "migrations";

    public static function setupMigrationTable() {
        $db = Database::getInstance();
        $db->exec("CREATE TABLE IF NOT EXISTS " . self::$migrationTable . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;");
    }

    public static function getExecutedMigrations() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT migration FROM " . self::$migrationTable);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function migrate() {
        self::setupMigrationTable();
        $db = Database::getInstance();
        $executedMigrations = self::getExecutedMigrations();

        try {
            foreach (self::$migrations as $name => $query) {
                if (!in_array($name, $executedMigrations)) {
                    $db->exec($query);
                    $db->exec("INSERT INTO " . self::$migrationTable . " (migration) VALUES ('$name')");
                    echo "Migration $name exécutée avec succès.\n";
                }
            }
        } catch (PDOException $e) {
            die("Erreur lors des migrations : " . $e->getMessage());
        }
    }

    public static function reset() {
        $db = Database::getInstance();
        try {
            $db->exec("DROP TABLE IF EXISTS pages, users, " . self::$migrationTable . ";");
            echo "Toutes les migrations ont été annulées.\n";
        } catch (PDOException $e) {
            die("Erreur lors de la suppression des tables : " . $e->getMessage());
        }
    }

    public static function next() {
        self::setupMigrationTable();
        $db = Database::getInstance();
        $executedMigrations = self::getExecutedMigrations();
        
        foreach (self::$migrations as $name => $query) {
            if (!in_array($name, $executedMigrations)) {
                $db->exec($query);
                $db->exec("INSERT INTO " . self::$migrationTable . " (migration) VALUES ('$name')");
                echo "Migration $name exécutée avec succès.\n";
                return;
            }
        }
        echo "Toutes les migrations ont déjà été exécutées.\n";
    }

    public static function previous() {
        self::setupMigrationTable();
        $db = Database::getInstance();
        $executedMigrations = self::getExecutedMigrations();
        
        if (!empty($executedMigrations)) {
            $lastMigration = end($executedMigrations);
            $db->exec("DELETE FROM " . self::$migrationTable . " WHERE migration = '$lastMigration'");
            echo "Migration $lastMigration annulée avec succès.\n";
        } else {
            echo "Aucune migration à annuler.\n";
        }
    }
}

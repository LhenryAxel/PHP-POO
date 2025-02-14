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
        ) ENGINE=InnoDB;",

        "003_create_structure_table" => "CREATE TABLE IF NOT EXISTS structure (
            id INT AUTO_INCREMENT PRIMARY KEY,
            head TEXT NOT NULL,
            header TEXT NOT NULL,
            footer TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;",
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

    public static function insertDefaultData() {
        $db = Database::getInstance();

        $stmt = $db->query("SELECT COUNT(*) FROM users WHERE email = 'admin@example.com'");
        $adminExists = $stmt->fetchColumn();

        $stmt = $db->query("SELECT COUNT(*) FROM users WHERE email = 'user@example.com'");
        $userExists = $stmt->fetchColumn();

        if ($adminExists == 0) {
            $hashedPassword = password_hash("admin123", PASSWORD_BCRYPT);
            $db->exec("INSERT INTO users (email, password, role) VALUES 
                ('admin@example.com', '$hashedPassword', 'admin')");
            echo "Admin user inserted.\n";
        }

        if ($userExists == 0) {
            $hashedPassword = password_hash("user123", PASSWORD_BCRYPT);
            $db->exec("INSERT INTO users (email, password, role) VALUES 
                ('user@example.com', '$hashedPassword', 'user')");
            echo "Normal user inserted.\n";
        }

        $stmt = $db->query("SELECT COUNT(*) FROM pages");
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $db->exec("INSERT INTO pages (title, slug, content, created_by) VALUES 
                ('Bienvenue', 'bienvenue', '<p>Bienvenue sur notre CMS !</p>', 1)");
            echo "Default page inserted.\n";
        }

        $stmt = $db->query("SELECT COUNT(*) FROM structure");
        $count = $stmt->fetchColumn();
        
        $head = 
        "<style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: Arial, sans-serif;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .main {
                background: white;
                margin: auto;
                width: 80%;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                text-align: left;
            }
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background-color:rgb(98, 160, 223);
                padding: 15px 20px;
                border-bottom: 2px solid #ddd;
            }
            .title {
                flex-grow: 1;
                text-align: center;
                font-size: 24px;
                font-weight: bold;
            }-
            .buttons {
                display: flex;
                gap: 10px;
            }
            .btn {
                padding: 8px 12px;
                border: none;
                cursor: pointer;
                border-radius: 5px;
                font-size: 14px;
            }
            .btn.edit {
                background-color: #007bff;
                color: white;
            }
            .btn.login {
                background-color: #28a745;
                color: white;
            }
            .btn:hover {
                opacity: 0.8;
            }
            .footer {
                background-color: #b0c4de;
                text-align: center;
                padding: 15px;
                margin-top: auto;
            }
            .footer .creators {
                margin: 10px 0;
            }
            .footer .btn {
                background-color: #333;
                color: white;
                padding: 8px 12px;
                text-decoration: none;
                display: inline-block;
                border-radius: 5px;
                margin-top: 10px;
            }
        </style>";

        $header = 
        "<header class=\"header\">
            <div class=\"title\">Wiki Universel</div>
            <div class=\"buttons\">
                <a href=\"index.php/page=update-pages\"><button class=\"btn edit\">Modifier</button></a>
                <a href=\"index.php/page=home\"><button class=\"btn login\">Se Connecter</button></a>
            </div>
        </header>";

        $footer =
        "<footer class=\"footer\">
            <p>&copy; 2025 Wiki Universel. Tous droits réservés.</p>
            <p class=\"creators\">Créateurs : Amin, Alex, Quentin, Thomas</p>
            <a href=\"index.php\" class=\"btn\">Retour</a>
        </footer>";

        if ($count == 0) {
            $db->exec("INSERT INTO structure (head, header, footer) VALUES 
                ('" . $head . "', 
                '" . $header . "', 
                '" . $footer . "')");
            echo "Default structure inserted.\n";
        }
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

        self::insertDefaultData();
    }

    public static function reset() {
        $db = Database::getInstance();
        try {
            $db->exec("DROP TABLE IF EXISTS pages, users, structure, " . self::$migrationTable . ";");
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

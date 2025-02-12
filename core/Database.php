<?php
namespace Core;

require_once __DIR__ . '/../core/Env.php';

use PDO;
use PDOException;

class Database {
    private static PDO|null $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::$instance = self::CreateConnection();
        }
        return self::$instance;
    }

    private static function CreateConnection(): PDO {
        try {
            loadEnv();

            return new PDO(
                self::GetDSN(),
                $_ENV["DB_USERNAME"],
                $_ENV["DB_PASSWORD"],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            exit("Erreur de connexion : " . $e->getMessage());
        }
    }

    private static function GetDSN(): string {
        return "mysql:host=".$_ENV["DB_HOST"].";dbname=".$_ENV["DB_NAME"].";port=".$_ENV["DB_PORT"].";charset=".$_ENV["DB_CHARSET"];
    }
}

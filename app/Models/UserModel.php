<?php
namespace App\Models;

require_once __DIR__ . '/../../core/Database.php';

use PDO;
use Exception;
use App\Models\Model;
use App\Models\Lists\ListUser;
use App\Models\Objects\User;
use Core\ListQueryParam;
use Core\QueryParam;
use PDOException;

class UserModel extends Model {

    public function getUserByEmail(string $email): User {
        try {
            $result = $this->db->GetOne(
                "SELECT * FROM users WHERE email = :email",
                new ListQueryParam(
                    new QueryParam(":email", $email, PDO::PARAM_STR)
                )
            );
        } catch (PDOException $e) {
            throw $e;
        }

        return User::NewObject($result);

        /*
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        */
    }

    public function createUser(string $email, string $password, string $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $hashedPassword, $role]);
    }

    public function GetAll(): ListUser {
        throw new Exception("Not Implemented Yet");
    }

	public function GetById(): ListUser {
        throw new Exception("Not Implemented Yet");
    }

	public function Update(): ListUser {
        throw new Exception("Not Implemented Yet");
    }

	public function Insert(User $User): bool {
        throw new Exception("Not Implemented Yet");
    }
}

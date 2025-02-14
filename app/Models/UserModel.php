<?php
namespace App\Models;

require_once __DIR__ . '/../../core/Database.php';

use App\Models\Objects\User;
use Core\Database;
use Core\Query\ListQueryParam;
use Core\Query\QueryParam;
use PDO;
use PDOException;
use Traits\Singleton;

class UserModel extends Model {
    use Singleton;

    public function GetById(int $id): User|null {
        try {
            return User::NewObject(
                Database::GetInstance()->GetOne(
                    "SELECT * FROM users WHERE id = :id",
                    new ListQueryParam(
                        new QueryParam("id", $id, PDO::PARAM_INT)
                    )
                )
            );
        } catch (PDOException $e) {
            return null;
        }
    }



    public function getUserByEmail(string $email): User|null {
        try {
            return User::NewObject(
                $this->db->GetOne(
                    "SELECT * FROM users WHERE email LIKE :email",
                    new ListQueryParam(
                        new QueryParam("email", $email, PDO::PARAM_STR)
                    )
                )
            );
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createUser(string $email, string $password, string $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $hashedPassword, $role]);
    }

    public function getUsers(){
        $stmt = $this->db->prepare("SELECT * FROM users;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id){
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

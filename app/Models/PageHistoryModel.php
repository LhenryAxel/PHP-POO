<?php

namespace App\Models;

require_once __DIR__ . '/../../core/Database.php';

use PDO;
use PDOException;

use Core\Database;
use Core\Query\QueryParam;
use Core\Query\ListQueryParam;

use App\Models\Objects\User;
use App\Models\Lists\ListUser;

use Traits\Singleton;

/**
 * Class handling manipulations of PageHistory
 */
class HistoryModel extends Model {
	use Singleton;

	public function GetById(int $id): User|null|false {
		try {
			return User::NewObject(
				Database::GetInstance()->GetOne(
					query: "SELECT * FROM users WHERE id = :id",
					QueryParams: new ListQueryParam(
						new QueryParam("id", $id, PDO::PARAM_INT)
					)
				)
			);
		} catch (PDOException $e) {
			return null;
		}
	}

	public function GetAll(): ListUser|false {
		try {
			return ListUser::NewList(
				$this->db->GetList(
					"SELECT * FROM users"
				)
			);
		} catch (PDOException $e) {
			return false;
		}
	}

	public function GetByEmail(string $email): User|null|false {
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
			return false;
		}
	}

	public function Create(string $email, string $password, string $role = 'user'): User|null|false {
		try {
			$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

			return User::NewObject(
				$this->db->GetOne(
					"INSERT INTO users (email, password, role) VALUES (:email, :hashedPassword, :role)",
					new ListQueryParam(
						new QueryParam(":email", $email, PDO::PARAM_STR),
						new QueryParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR),
						new QueryParam(":role", $role, PDO::PARAM_STR),
					)
				)
			);
		} catch (PDOException $e) {
			return false;
		}
	}

	public function Delete ($id): bool {
		try {
			return $this->db->Delete(
				"DELETE FROM users WHERE id = :id",
				new ListQueryParam(
					new QueryParam(":id", $id, PDO::PARAM_INT),
				)
			);
		} catch (PDOException $e) {
			return false;
		}
	}
}

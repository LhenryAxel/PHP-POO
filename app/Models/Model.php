<?php
namespace App\Models;

use Core\Database;
use DatabaseObject;
use PDO;
use App\Models\Lists\ListDatabaseObject;
use Traits\Singleton;

abstract class Model
{
	abstract public static function GetInstance();
	protected Database $db;

	public function __construct() {
		$this->db = Database::GetInstance();
	}
}
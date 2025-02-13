<?php
namespace App\Models;

use Core\Database;
use DatabaseObject;
use PDO;
use App\Models\Lists\ListDatabaseObject;

abstract class Model
{
	protected Database $db;

	public function __construct() {
		$this->db = Database::getInstance();
	}
}
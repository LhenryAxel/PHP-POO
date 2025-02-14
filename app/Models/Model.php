<?php
namespace App\Models;

use Core\Database;

abstract class Model
{
	abstract public static function GetInstance();
	protected Database $db;

	public function __construct() {
		$this->db = Database::GetInstance();
	}
}
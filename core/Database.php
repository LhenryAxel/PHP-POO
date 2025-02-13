<?php
namespace Core;

require_once __DIR__ . '/../core/Env.php';

use PDO;
use PDOException;
use Traits\Singleton;
use Core\QueryParam;
use Core\ListQueryParam;
use PDOStatement;

/**
 * A singleton class managing the access to the database;
 */
class Database extends PDO {
	use Singleton;

	final private function __construct() {
		loadEnv();

		try {
			parent::__construct(
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

	private function HandleQuery(string $query, ListQueryParam $QueryParams): PDOStatement {
		try {
			$Statement = $this->prepare($query);
			self::BindQueryParamList($Statement, $QueryParams);
			$Statement->execute();
		} catch (PDOException $e) {
			throw $e;
		}
		
		return $Statement;
	}

	public static function BindQueryParamList(PDOStatement $Statement, ListQueryParam $QueryParams): void {
		try {
			foreach ($QueryParams as $QueryParam) {
				$QueryParam->BindToStatement($Statement);
			}
		} catch (PDOException $e) {
			throw $e;
		}
	}
	
	public function GetList(string $query, ListQueryParam $QueryParams): array {
		if (stripos($query, "select") != 0) {
			throw new PDOException("'$query' is not selection query");
		}
		
		try {
			$Statement = $this->HandleQuery($query, $QueryParams);
		} catch (PDOException $e) {
			throw $e;
		}
		
		$data = $Statement->fetchAll(PDO::FETCH_ASSOC);
		
		if ($data === false) {
			throw new PDOException("Fetching the data failed");
		}
		
		return $data;
	}

	public function GetOne(string $query, ListQueryParam $QueryParams): array {
		if (stripos($query, "select") != 0) {
			throw new PDOException("'$query' is not selection query");
		}

		try {
			$Statement = $this->HandleQuery($query, $QueryParams);
		} catch (PDOException $e) {
			throw $e;
		}
		
		$data = $Statement->fetch(PDO::FETCH_ASSOC);

		if ($data === false) {
			throw new PDOException("Fetching the data failed");
		}

		return $data;
	}

	public function InsertList(string $query, ListQueryParam $QueryParams): bool {
		if (stripos($query, "insert") != 0 && stripos($query, "values") != false) {
			throw new PDOException("'$query' is not insertion query");
		}

		try {
			$Statement = $this->HandleQuery($query, $QueryParams);
		} catch (PDOException $e) {
			throw $e;
		}
		
		return $Statement->rowCount() > 0;
	}

	public function InsertOne(string $query, ListQueryParam $QueryParams): int {
		if (stripos($query, "insert") != 0 && stripos($query, "values") != false) {
			throw new PDOException("'$query' is not insertion query");
		}

		try {
			$this->HandleQuery($query, $QueryParams);
		} catch (PDOException $e) {
			throw $e;
		}
		
		return $this->lastInsertId();
	}

	public function UpdateQuery(string $query, ListQueryParam $QueryParams): int {
		if (stripos($query, "update") != 0 && stripos($query, "set") != false) {
			throw new PDOException("'$query' is not update query");
		}

		try {
			$this->HandleQuery($query, $QueryParams);
		} catch (PDOException $e) {
			throw $e;
		}
		
		return $this->lastInsertId();
	}

	/**
	 * Prepare and execute a delete query, by security if the query need to include a where clause or an PDOException is thrown
	 * @param string $query
	 * @param \Core\ListQueryParam $QueryParams
	 * @throws \PDOException
	 * @return bool|string
	 */
	public function DeleteQuery(string $query, ListQueryParam $QueryParams): int {
		if (stripos($query, "delete from") != 0) {
			throw new PDOException("'$query' is not deletion query");
		}

		if (stripos($query, "where") != false) {
			throw new PDOException(
				"By the security the '$query' deletion query need an where clause, its execution will be omitted"
			);
		}

		$this->HandleQuery($query, $QueryParams);

		return $this->lastInsertId();
	}
}

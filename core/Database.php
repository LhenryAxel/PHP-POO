<?php
namespace Core;

require_once __DIR__ . '/../core/Env.php';
require_once __DIR__ . '/../traits/singleton.php';

use PDO;
use PDOException;
use PDOStatement;

use Traits\Singleton;

use Core\Query\ListQueryParam;
use Core\Query\QueryParam;
use Core\Query\QueryErrorCode;
use Core\Query\QueryException;
use Core\Query\QueryType;

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

	#region gestion des requêtes

	public function Execute(string $query, QueryType $QueryType = QueryType::UNKNOWN) {
		try {
			switch ($QueryType) {
				case QueryType::UNKNOWN:
					$QueryType = QueryType::getQueryType($query);
				
				case QueryType::GET_ONE:
					return $this->getOne($query);
				
				case QueryType::GET_LIST:
					return $this->getList($query);
				
				case QueryType::GET_COLLUMN:
					return $this->getCollumn($query);
				
				case QueryType::INSERT_ONE:
					return $this->InsertOne($query);
				
				case QueryType::INSERT_LIST:
					return $this->InsertList($query);
				
				case QueryType::UPDATE:
					return $this->update($query);
				
				case QueryType::DELETE:
					return $this->delete($query);
				
				default:
					throw new QueryException(
						"Ce type de requête n'est pas supporté ou n'est pas valide",
						QueryType::UNKNOWN,
						QueryErrorCode::UNKNOWN,
					);
			}
		} catch (QueryException $e) {
			throw $e;
		}
	}


	/**
	 * Summary of prepare
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Core\Query\QueryException
	 * @return PDOStatement
	 */
	public function HandlePrepare(string $query, array $options): PDOStatement { 
		try {
			$Statement = $this->prepare($query);
		} catch (PDOException $e) {
			throw new QueryException(
				"La péparation a échouée, exception \n<br> $e",
				QueryType::UNKNOWN, 
				QueryErrorCode::PREPARATION_FAILED,
				$e
			);
		}
		
		if ($Statement === false) {
			throw new QueryException(
				"La péparation a échouée, exception inconnue",
				QueryType::UNKNOWN,
				QueryErrorCode::PREPARATION_FAILED,
			);
		}
		
		return $Statement;
	}


	public function HandleBindingParamList(PDOStatement $Statement, ListQueryParam $QueryParams): bool {
		foreach ($QueryParams as $QueryParam) {
			try {
				$this->HandleBindingParam($Statement, $QueryParam);
			} catch (QueryException $e) {
				throw $e;
			}
		}
		return true;
	}


	public function HandleBindingParam(PDOStatement $Statement, QueryParam $QueryParam): bool {
		if (!$QueryParam->IsBindPossible($Statement)) {
			throw new QueryException(
				"Impossible to bind the parametter \"$QueryParam\" in the statement",
				QueryType::UNKNOWN,
				QueryErrorCode::BINDING_FAILED,
			);
		}

		if (!$QueryParam->BindToStatement($Statement)) {
			throw new QueryException(
				"The binding failed",
				QueryType::UNKNOWN,
				QueryErrorCode::BINDING_FAILED
			);
		}

		return true;
	}


	protected function SendQuery(string $query, ListQueryParam|null $QueryParams = null): PDOStatement {
		try {
			$Statement = $this->prepare($query);

			if ($QueryParams != null) {
				$this->HandleBindingParamList($Statement, $QueryParams);
			}
			
			$this->HandleExecution($Statement);
			return $Statement;
		} catch (QueryException $e) {
			throw new QueryException(
				$e->getMessage(),
				QueryType::UNKNOWN,
				$e->getCode(),
				$e
			);
		}
	}


	/**
	 * Summary of getOne
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Core\Query\QueryException
	 * @return array
	 */
	public function getOne(string $query, ListQueryParam|null $QueryParams = null): array {
		if (!QueryType::IsSelectionQuery($query)) {
			throw new QueryException(
				"Requête invalide, ce n'est pas une requête de selection",
				QueryType::UNKNOWN,
				QueryErrorCode::INVALID_REQUEST_TYPE,
			);
		}

		try {
			$Statement = $this->SendQuery($query, $QueryParams);
			return $this->HandleFetch($Statement);
		} catch (QueryException $e) {
			throw new QueryException(
				$e, 
				QueryType::GET_ONE, 
				$e->getCode(), 
				$e
			);
		}
	}


	/**
	 * Summary of getList
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Exception
	 * @return array
	 */
	public function GetList(string $query, ListQueryParam|null $QueryParams = null): array {
		if (!QueryType::IsSelectionQuery($query)) {
			throw new QueryException(
				"Requête invalide, ce n'est pas une requête de selection",
				QueryType::GET_LIST,
				QueryErrorCode::INVALID_REQUEST_TYPE,
			);
		}

		try {
			$Statement = $this->SendQuery($query, $QueryParams);
			return $this->HandleFetchAll($Statement);
		} catch (QueryException $e) {
			throw new QueryException(
				$e,
				QueryType::GET_LIST,
				$e->getCode(),
				$e
			);
		}
	}

	
	/**
	 * Summary of HandleGetCollumn
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Core\Query\QueryException
	 * @return array
	 */
	public function GetCollumn(string $query, ListQueryParam|null $QueryParams = null): array {
		if (!QueryType::isSelectionQuery($query)) {
			throw new QueryException(
				"Requête invalide, ce n'est pas une requête de selection",
				QueryType::GET_COLLUMN, 
				QueryErrorCode::INVALID_REQUEST_TYPE
			);
		}

		try {
			$Statement = $this->SendQuery($query, $QueryParams);
			return $this->HandleFetchCollumn($Statement);
		} catch (QueryException $e) {
			throw new QueryException(
				$e, 
				QueryType::GET_COLLUMN, 
				$e->getCode(), 
				$e
			);
		}
	}


	/**
	 * Summary of InsertOne
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Core\Query\QueryException
	 * @return int
	 */
	public function InsertOne(string $query, ListQueryParam|null $QueryParams = null): int {
		if (!QueryType::isInsertionQuery($query)) {
			throw new QueryException(
				"Requête invalide, ce n'est pas une requête de insertion",
				QueryType::INSERT_ONE,
				QueryErrorCode::INVALID_REQUEST_TYPE
			);
		}

		try {
			$this->SendQuery($query, $QueryParams);
			return $this->lastInsertId();
		} catch (QueryException $e) {
			throw new QueryException(
				$e->getMessage(), 
				QueryType::INSERT_ONE, 
				$e->getCode(),
				$e
			);
		}
	}
	
	
	/**
	 * Summary of insert
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Core\Query\QueryException
	 * @return int
	 */
	public function InsertList(string $query, ListQueryParam|null $QueryParams = null): int {
		if (!QueryType::isInsertionQuery($query)) {
			throw new QueryException(
				"Requête invalide, ce n'est pas une requête de insertion",
				QueryType::INSERT_LIST,
				QueryErrorCode::INVALID_REQUEST_TYPE
			);
		}
		
		try {
			$Statement = $this->SendQuery($query, $QueryParams);
			return $this->HandleRowModification($Statement);
		} catch (QueryException $e) {
			throw new QueryException(
				"", 
				QueryType::INSERT_LIST, 
				$e->getCode(),
				$e
			);
		}
	}
	

	/**
	 * Summary of update
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Core\Query\QueryException
	 * @return int
	 */
	public function Update(string $query, ListQueryParam|null $QueryParams = null): int {
		if (!QueryType::isUpdateQuery($query)) {
			throw new QueryException(
				"Requête invalide, ce n'est pas une requête de mise à jour",
				QueryType::UPDATE,
				QueryErrorCode::INVALID_REQUEST_TYPE
			);
		}
		
		try {
			$Statement = $this->SendQuery($query, $QueryParams);
			return $this->HandleRowModification($Statement);
		} catch (QueryException $e) {
			throw new QueryException(
				$e, 
				QueryType::UPDATE, 
				$e->getCode(), 
				$e
			);
		}
	}


	/**
	 * Summary of delete
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Core\Query\QueryException
	 * @return int
	 */
	public function Delete(string $query, ListQueryParam|null $QueryParams = null): int {
		if (!QueryType::isDeletionQuery($query)) {
			throw new QueryException(
				"Requête invalide, ce n'est pas une requête de suppression", 
				QueryType::DELETE, 
				QueryErrorCode::INVALID_REQUEST_TYPE, 
			);
		}
		
		try {
			$Statement = $this->SendQuery($query, $QueryParams);
			return $this->HandleRowModification($Statement);
		} catch (QueryException $e) {
			throw new QueryException(
				$e, 
				QueryType::DELETE, 
				$e->getCode(), 
				$e
			);
		}
	}


	/**
	 * Summary of HandleExecution
	 * @param PDOStatement $Statement
	 * @throws \Core\Query\QueryException
	 * @return void
	 */
	protected function HandleExecution(PDOStatement $Statement): bool {
		try {
			return $Statement->execute();
		} catch (PDOException $e) {
			throw new QueryException(
				"La requête a échouée, exception : \n<br> $e", 
				QueryType::UNKNOWN, 
				QueryErrorCode::EXECUTION_FAILED, 
				$e
			);
		}
	}


	/**
	 * Summary of HandleFetchAll
	 * @param PDOStatement $Statement
	 * @throws \Core\Query\QueryException
	 * @return array
	 */
	protected function HandleFetchAll(PDOStatement $Statement): array {
		$datas = $Statement->fetchAll(PDO::FETCH_ASSOC);
		
		if ($datas === false) {
			throw new QueryException(
				"Impossible de récupérer les données de la base de donnée",
				QueryType::GET_LIST,
				QueryErrorCode::FETCH_FAILED
			);
		}
		
		return $datas;
	}


	/**
	 * Summary of HandleFetch
	 * @param PDOStatement $Statement
	 * @throws \Core\Query\QueryException
	 * @return array
	 */
	protected function HandleFetch(PDOStatement $Statement): array {
		$data = $Statement->fetch(PDO::FETCH_ASSOC);

		if ($data === false) {
			throw new QueryException(
				"Impossible de récupérer les données de la base de donnée",
				QueryType::GET_ONE,
				QueryErrorCode::FETCH_FAILED
			);
		}
		return $data;
	}


	/**
	 * Summary of HandleFetchCollumn
	 * @param PDOStatement $Statement
	 * @throws \Core\Query\QueryException
	 * @return array
	 */
	protected function HandleFetchCollumn(PDOStatement $Statement): array {
		$data = $Statement->fetchColumn();

		if ($data === false) {
			throw new QueryException(
				"Impossible de récupérer les données de la base de donnée",
				QueryType::GET_COLLUMN,
				QueryErrorCode::FETCH_FAILED
			);
		}

		$datas = [];
		array_push($datas, $data);

		while ($data != false) {
			$data = $Statement->fetchColumn();
			array_push($datas, $data);
		}
		
		return $datas;
	}


	/**
	 * Handle the responce to return from query resulting in the modification of rows
	 * @param PDOStatement $Statement
	 * @throws \Core\Query\QueryException
	 * @return int number of affected rows
	 */
	protected function HandleRowModification(PDOStatement $Statement): int {
		return $Statement->rowCount();
	}

	#endregion
}

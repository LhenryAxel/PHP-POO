<?php
namespace Core\Query;

use PDOException;
use PDOStatement;
use PDO;

class QueryParam
{
	private string $bind;
	private string $value;
	private int|null $type;

	#region Getters
	public function GetBind(): string {
		return $this->bind;
	}

	public function GetValue(): string {
		return $this->value;
	}

	public function GetType(): int {
		return $this->type;
	}

	#endregion

	#region Setters
	public function SetBind(string $bind): void {
		if ($bind === "") {
			throw new PDOException("A bind cannot be empty");
		}

		if (!str_starts_with($bind, ":")) {
			$bind = ":$bind";
		}

		$this->bind = $bind;
	}

	public function SetValue(string $value): void {
		$this->value = $value;
	}

	public function SetType(int $type): void {
		$this->type = $type;
	}

	
	#endregion


	public function __construct(string $bind, string $value, int|null $type = PDO::PARAM_STR) {
		$this->SetBind($bind);
		$this->SetValue($value);
		$this->SetType($type);
	}


	public function BindToStatement(PDOStatement $statement): bool {
		try {
			return $statement->bindValue($this->bind, $this->value, $this->type ?? PDO::PARAM_STR);
		} catch (PDOException $e) {
			throw $e;
		}
	}

	
	public function IsBindPossible(PDOStatement $Statement): bool {
		if ($Statement->queryString === "") {
			throw new QueryException(
				"La requête n'est pas dénfinie",
				QueryType::UNKNOWN,
				QueryErrorCode::BINDING_FAILED,
			);
		}

		if (!str_starts_with($this->bind, ":")) {
			$this->bind = ":$this->bind";
		}

		if (!str_contains($Statement->queryString, $this->bind)) {
			throw new QueryException(
				"Le paramètre de requête ne peut pas être attaché à la requête",
				QueryType::UNKNOWN,
				QueryErrorCode::BINDING_FAILED,
			);
		}

		// Message::info("$this->bind can be bind");

		return true;
	}

	public function __tostring(): string {
		return "$this->bind => $this->value";
	}
}
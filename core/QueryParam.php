<?php
namespace Core;

use PDOException;
use PDOStatement;
use PDO;
use Traits\AppendList;

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


	public function __construct(string $bind, string $value, int|null $type = null) {
		$this->bind = $bind;
		$this->value = $value;
		$this->type = $type;
	}


	public function BindToStatement(PDOStatement $statement): void {
		try {
			$statement->bindValue($this->bind, $this->value, $this->type ?? PDO::PARAM_STR);
		} catch (PDOException $e) {
			throw $e;
		}
	}
}

class ListQueryParam extends ListAbstract
{
	use AppendList {
		AppendList as AppendQueryParams;
	}

	public function __construct(QueryParam ...$QueryParams) {
		$this->values = $QueryParams;
	}

	public function Add(QueryParam ...$QueryParams): void {
		$this->values = array_merge($this->values, $QueryParams);
	}

	public function Remove(QueryParam ...$QueryParams): void {
		foreach ($QueryParams as $QueryParam) {
			$key = array_search($QueryParam, $this->values);

			if ($key === false) {
				continue;
			}

			array_slice($this->values, $key, 1);
		}
	}

	public function current(): QueryParam {
		return parent::current();
	}
}
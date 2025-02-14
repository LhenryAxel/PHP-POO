<?php
namespace Core\Query;

use Core\Query\QueryParam;
use Core\ListAbstract;

class ListQueryParam extends ListAbstract
{
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
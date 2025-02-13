<?php
namespace Core;

use Core\QueryParam;
use Core\ListAbstract;
use Traits\AppendList;

class ListQueryParam extends ListAbstract
{
	// use AppendList {
	// 	Append as AppendQueryParams;
	// }

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
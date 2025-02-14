<?php
namespace App\Models\Lists;

use Traits\AppendList;
use App\Models\Lists\ListDatabaseObject;
use App\Models\Objects\PageHistory;

final class ListPageHistory extends ListDatabaseObject
{
	use AppendList {
		Append as AppendPageHistory;
	}

	public function __construct(PageHistory ...$PageHistorys) {
		$this->values = $PageHistorys;
	}

	public function Add(PageHistory ...$PageHistorys): void {
		$this->values = array_merge($this->values, $PageHistorys);
	}

	public function Remove(PageHistory ...$PageHistorys): void {
		foreach ($PageHistorys as $PageHistory) {
			$key = array_search($PageHistory, $this->values);

			if ($key === false) {
				continue;
			}

			array_slice($this->values, $key, 1);
		}
	}

	public function current(): PageHistory {
		return parent::current();
	}
	
	public static function NewList(array $data, bool $associateReference = false): self {
		$result = new ListPageHistory();
		foreach ($data as $d) {
			$result->Add(PageHistory::NewObject($d, $associateReference));
		}

		return $result;
	}
}
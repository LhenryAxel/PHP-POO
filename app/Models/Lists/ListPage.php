<?php
namespace App\Models\Lists;

use Traits\AppendList;
use App\Models\Lists\ListDatabaseObject;
use App\Models\Objects\Page;

class ListPage extends ListDatabaseObject
{
	use AppendList;

	public function __construct(Page ...$Pages) {
		$this->values = $Pages;
	}

	public function Add(Page ...$Pages): void {
		$this->values = array_merge($this->values, $Pages);
	}

	public function Remove(Page ...$Pages): void {
		foreach ($Pages as $Page) {
			$key = array_search($Page, $this->values);

			if ($key === false) {
				continue;
			}

			array_slice($this->values, $key, 1);
		}
	}

	public function current(): Page {
		return parent::current();
	}
}
<?php
namespace App\Models\Lists;

use Traits\AppendList;
use App\Models\Lists\ListDatabaseObject;
use App\Models\Objects\User;

class ListUser extends ListDatabaseObject
{
	use AppendList {
		Append as AppendUser;
	}

	public function __construct(User ...$Users) {
		$this->values = $Users;
	}

	public function Add(User ...$Users): void {
		$this->values = array_merge($this->values, $Users);
	}

	public function Remove(User ...$Users): void {
		foreach ($Users as $User) {
			$key = array_search($User, $this->values);

			if ($key === false) {
				continue;
			}

			array_slice($this->values, $key, 1);
		}
	}

	public function current(): User {
		return parent::current();
	}
}
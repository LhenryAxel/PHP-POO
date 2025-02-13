<?php
namespace App\Models\Lists;

use Core\ListAbstract;
use Traits\AppendList;

abstract class ListDatabaseObject extends ListAbstract
{
	use AppendList {
		AppendList as AppendListDatabaseObject;
	}
}
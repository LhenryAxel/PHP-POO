<?php

namespace Traits;

trait AppendList {
	abstract public function GetValues(): array;
	public function Append(self $List): void {
		$this->values = array_merge($this->values, $List->GetValues());
	}
}
<?php
namespace Core;

use Iterator, Countable;

abstract class ListAbstract implements Iterator, Countable
{
	protected array $values = [];
	private int $currentIndex = 0;

	public function GetValues(): array {
		return $this->values;
	}

	abstract public function __construct();
	abstract public function Add();
	abstract public function Remove();

	public function IsEmpty(): bool {
		return $this->values === [];
	}


	public function current(): mixed {
		return $this->values[$this->currentIndex];
	}
	
	final public function key(): int {
		return $this->currentIndex;
	}

	final public function next(): void {
		$this->currentIndex++;
	}

	final public function rewind(): void {
		$this->currentIndex = 0;
	}

	final public function valid(): bool {
		return isset($this->values[$this->currentIndex]);
	}

	final public function count(): int {
		return count($this->values);
	}

	public function __toString(): string {
		$str = "";
		foreach ($this->values as $value) {
			$str .= $value;
		}
		return $str;
	}
}

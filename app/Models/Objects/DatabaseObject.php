<?php

abstract class DatabaseObject
{
	abstract public function __construct();

	/**
	 * Create an new database object from a database request
	 * @param array $data the data fetched from the database
	 * @return self the database object
	 */
	abstract public static function NewObject(array $data): self;
}
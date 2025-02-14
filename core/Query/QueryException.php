<?php
namespace Core\Query;

use Core\Query\QueryType;
use Exception;


/**
 * An exception class to handle an unexpected behavior in while handling a query 
 * with {@see Core\Database} with specific functions can execute properly
 */
class QueryException extends Exception {
	public QueryType $QueryType;
	public function __construct($message, QueryType $QueryType, int|QueryErrorCode $code = 0, $previous = null) {
		if ($code instanceof QueryErrorCode) {
			parent::__construct($message, $code->value, $previous);
		} else {
			parent::__construct($message, $code, $previous);
		}

		$this->QueryType = $QueryType;
	}

	public function getQueryType(): QueryType {
		return $this->QueryType;
	}
}

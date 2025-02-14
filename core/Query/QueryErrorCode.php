<?php
namespace Core\Query;

/**
 * An enumeration of all error code returns by 
 */
enum QueryErrorCode : int {
	case UNKNOWN = 0;

	/**
	 * If the query type is invalid
	 */
	case INVALID_REQUEST_TYPE = 1;

	/**
	 * If the query preparation failed
	 */
	case PREPARATION_FAILED = 2;

	/**
	 * If the binding failed
	 */
	case BINDING_FAILED = 3;

	/**
	 * If the query execution failed
	 */
	case EXECUTION_FAILED = 4;

	/**
	 * If the row selection query (Select) failed
	 */
	case FETCH_FAILED = 5;
	/**
	 * If the row modification query (Insert | Update | Delete) failed
	 */
	case MODIFICATION_FAILED = 6;

	/**
	 * If the execution didn't affect any rows
	 */
	case NOT_FOUND = 7;
}
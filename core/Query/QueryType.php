<?php
namespace Core\Query;

/**
 * All types of query that can be handle by {@see Core\Database} new query handling functions
 */
enum QueryType
{
	/**
	 * undefinded query type
	 * @var int
	 */
	case UNKNOWN;

	/**
	 * Query type used to return the first/only result of a query
	 * @var int
	 */
	case GET_ONE;

	/**
	 * Query type used to return all result of a query
	 * @var int
	 */
	case GET_LIST;

	/**
	 * Query type used to return a single collumn of a query
	 * @var int
	 */
	case GET_COLLUMN;

	/**
	 * Query type used to insert a single row and getting back is given ID
	 * @var int
	 */
	case INSERT_ONE;

	/**
	 * Query type used to insert a multiple rows and getting back is number of inserted rows
	 * @var int
	 */
	case INSERT_LIST;

	/**
	 * Query type used to update a multiple row and getting back is number of updated rows
	 * @var int
	 */
	case UPDATE;

	/**
	 * Query type used to update a multiple row and getting back is number of updated rows
	 * @var int
	 */
	case DELETE;

	/**
	 * Verify if the given query can be used as a selection query
	 * @param string $query the query to verify
	 * @return bool true if it is a selection query else false
	 */
	public static function IsSelectionQuery(string $query): bool {
		return str_starts_with($query, "SELECT");
	}

	/**
	 * Verify if the given query can be used as a insertion query
	 * @param string $query the query to verify
	 * @return bool true if it is a insetion query else false
	 */
	public static function IsInsertionQuery(string $query): bool {
		return str_starts_with($query, "INSERT INTO");
	}

	/**
	 * Verify if the given query can be used as a update query
	 * @param string $query the query to verify
	 * @return bool true if it is a update query else false
	 */
	public static function IsUpdateQuery(string $query): bool {
		return str_starts_with($query, "UPDATE") && str_contains($query, "SET");
	}

	/**
	 * Verify if the given query can be used as a deletion query
	 * @param string $query the query to verify
	 * @return bool true if it is a deletion query else false
	 */
	public static function IsDeletionQuery(string $query): bool {
		return str_starts_with($query, "DELETE FROM");
	}

	/**
	 * Verify if the given query can be used as a deletion query and include a where clause
	 * @param string $query the query to verify
	 * @return bool true if it is a deletion query else false
	 */
	public static function IsSecuredDeletionQuery(string $query): bool {
		return self::IsSecuredDeletionQuery($query) && str_contains($query, "WHERE");
	}

	public static function getQueryType(string $query): QueryType {
		if (self::IsSelectionQuery($query)) {
			return QueryType::GET_LIST;
		}
		if (self::IsInsertionQuery($query)) {
			return QueryType::INSERT_LIST;
		}
		if (self::IsUpdateQuery($query)) {
			return QueryType::UPDATE;
		}
		if (self::IsDeletionQuery($query)) {
			return QueryType::DELETE;
		}
		return QueryType::UNKNOWN;
	}
}
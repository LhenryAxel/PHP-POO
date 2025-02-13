<?php

namespace Traits;

/**
 * A trait making sure a class can only have one single instance
 */
trait Singleton
{
	use Unclonable;

	/**
	 * The singleton instance
	 * @var |null
	 */
	private static self|null $instance;


	/**
	 * Get or create the instance of the singleton this instance
	 */
	final public static function GetInstance(): static {
		// if (self::$instance === null) {
		//         self::$instance = new self();
		// }
		
		return self::$instance ?? self::$instance = new self();
	}

	abstract protected function __construct();
}
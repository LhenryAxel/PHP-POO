<?php

namespace Traits;

use Exception;

/**
 * A trait making a class un clonable
 */
trait Unclonable
{
        final private function __clone(): void {
                throw new Exception("The class is unclonable");
        }
}

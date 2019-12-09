<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

use Stk2k\File\File;

class RouterConfigFileNotFoundException extends RouterException
{
    /**
     * RouterConfigFileNotFoundException constructor.
     *
     * @param File $file
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( $file, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Router config file not found: $file", $code, $prev );
    }
}


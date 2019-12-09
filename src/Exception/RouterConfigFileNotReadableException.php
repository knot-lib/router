<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

use Stk2k\File\File;

class RouterConfigFileNotReadableException extends RouterException
{
    /**
     * RouterConfigFileNotReadableException constructor.
     *
     * @param File $file
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( $file, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Router config file is not readable: $file", $code, $prev );
    }
}


<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

use Stk2k\File\File;

class UnsupportedCacheConfigFileFormatException extends RouterException
{
    /**
     * UnsupportedCacheConfigFileFormatException constructor.
     *
     * @param File $file
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( $file, int $code = 0, Throwable $prev = null )
    {
        parent::__construct( "Unsupported cache config file format: $file", $code, $prev );
    }
}



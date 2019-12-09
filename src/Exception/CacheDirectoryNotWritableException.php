<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

class CacheDirectoryNotWritableException extends RouterException
{
    /**
     * CacheDirectoryNotFoundException constructor.
     *
     * @param string $cache_dir
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( $cache_dir, int $code = 0, Throwable $prev = null )
    {
        parent::__construct( "Cache directory not writable: $cache_dir", $code, $prev );
    }
}



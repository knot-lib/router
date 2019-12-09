<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

class CacheDirectoryConfigurationException extends RouterException
{
    /**
     * CacheDirectoryConfigurationException constructor.
     *
     * @param string $cache_dir
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( $cache_dir, int $code = 0, Throwable $prev = null )
    {
        parent::__construct( "Invalid cache directory config: $cache_dir", $code, $prev );
    }
}



<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

class RouterBuildingException extends RouterException
{
    /**
     * RouterBuildingException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( string $message, int $code = 0, Throwable $prev = null )
    {
        parent::__construct( 'Router building failed: ' . $message, $code, $prev );
    }
}



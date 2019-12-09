<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

class RouterNodeBuilderException extends RouterException
{
    /**
     * RouterNodeBuilderException constructor.
     *
     * @param string $routing_rule
     * @param string $message
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( string $routing_rule, string $message, int $code = 0, Throwable $prev = null )
    {
        parent::__construct( "Building router node tree failed($message): $routing_rule", $code, $prev );
    }
}



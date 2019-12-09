<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

class RoutingRuleParseException extends RouterException
{
    /**
     * RoutingRuleParseErrorException constructor.
     *
     * @param string $expr
     * @param string $message
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( string $expr, string $message, int $code = 0, Throwable $prev = null )
    {
        parent::__construct( "Parse Error: $expr($message)", $code, $prev );
    }
}



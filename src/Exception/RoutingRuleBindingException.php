<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

class RoutingRuleBindingException extends RouterException
{
    /**
     * RoutingRuleBindingException constructor.
     *
     * @param string $routing_rule
     * @param string $message
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( string $routing_rule, string $message, int $code = 0, Throwable $prev = null )
    {
        parent::__construct( "Routing rule binding failed: $routing_rule", $code, $prev );
    }
}



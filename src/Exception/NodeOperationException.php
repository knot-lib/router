<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

class NodeOperationException extends RouterException
{
    /**
     * NodeOperationException constructor.
     *
     * @param string $operation
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $operation, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "$operation to normal node is not allowed.", $code, $prev );
    }
}


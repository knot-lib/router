<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

use KnotLib\Exception\KnotPhpException;
use KnotLib\Exception\Runtime\RuntimeExceptionInterface;

class RouterException extends KnotPhpException implements RouterExceptionInterface, RuntimeExceptionInterface
{
    /**
     * RouterConfigFileNotReadableException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct(string $message, int $code = 0, Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);
    }
}


<?php
declare(strict_types=1);

namespace KnotLib\Router\Exception;

use Throwable;

class SerializedDataCorruptedException extends RouterException
{
    /**
     * SerializedDataCorruptedException constructor.
     *
     * @param string $file
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( $file, int $code = 0, Throwable $prev = null )
    {
        parent::__construct( "Serialized data currupted: $file", $code, $prev );
    }
}



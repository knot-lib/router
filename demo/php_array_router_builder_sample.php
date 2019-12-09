<?php
$base_dir = dirname(__DIR__);

require_once $base_dir . '/vendor/autoload.php';
require_once $base_dir . '/demo/autoloaders/autoloader.php';
require_once $base_dir . '/demo/include/global_handlers.php';

use KnotLib\Router\Builder\PhpArrayRouterBuilder;
use KnotLib\Router\Router;

$start = microtime(true);

try{
    $router = (new PhpArrayRouterBuilder(
        new Router('global_dispatcher'),
        [
            '/()' => ['GET' => 'home'],
            '/product/sample/:product_id' => ['GET' => 'product'],
            '/download/:file_id:\d+:int' => ['GET' => 'download'],
            '/math/PI/:pi:[0-9\.]+:float' => ['GET' => 'math'],
            '/fruits/:fruits[]' => ['GET' => 'fruits'],
        ]
    ))->build();

    require_once $base_dir . '/demo/include/simple_routes.php';
}
catch(Throwable $e)
{
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}

$end = microtime(true);
echo 'time=' . round(($end-$start)*1000, 3) . 'msec' . PHP_EOL;

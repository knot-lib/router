<?php
$base_dir = dirname(__DIR__);

require_once $base_dir . '/vendor/autoload.php';
require_once $base_dir . '/demo/autoloaders/autoloader.php';
require_once $base_dir . '/demo/include/global_handlers.php';

use KnotLib\Router\Router;
use KnotLib\Router\Builder\PhpArrayRouterBuilder;
use KnotLib\Router\DispatcherInterface;

$start = microtime(true);

class MyDispatcher implements DispatcherInterface
{
    public function dispatch(string $path, array $vars, string $event)
    {
        switch($event){
            case 'internal_server_error':
                echo "500 Internal Server Error" . PHP_EOL;
                break;
            case Router::ROUTE_NOT_FOUND:
                echo "404 Not Found" . PHP_EOL;
                break;
            case 'home':
                echo "home" . PHP_EOL;
                break;
            case 'product':
                $product_id = $vars['product_id'] ?? null;
                $type = gettype($product_id);
                echo "product product_id: $product_id($type)" . PHP_EOL;
                break;
            case 'download':
                $file_id = $vars['file_id'] ?? null;
                $type = gettype($file_id);
                echo "download file_id: $file_id($type)" . PHP_EOL;
                break;
            case 'math':
                $pi = $vars['pi'] ?? null;
                $type = gettype($pi);
                echo "math file_id: $pi($type)" . PHP_EOL;
                break;
            case 'fruits':
                $fruits = $vars['fruits'] ?? null;
                $type = gettype($fruits);
                echo 'fruits : ' . implode(' ,',$fruits) . "($type)" . PHP_EOL;
                break;
        }
    }
}

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










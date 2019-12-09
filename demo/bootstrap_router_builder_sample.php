<?php
$base_dir = dirname(__DIR__);

require_once $base_dir . '/vendor/autoload.php';
require_once $base_dir . '/demo/autoloaders/autoloader.php';
require_once $base_dir . '/demo/include/global_handlers.php';

use KnotLib\Router\Builder\BootstrapRouterBuilder;
use KnotLib\Router\Router;

try{
    $start = microtime(true);

    $router = (new BootstrapRouterBuilder(new Router('global_dispatcher'), function(Router $router){
        
        $router->leaf('()')->bind('GET', 'home');
        $router->node('product')->node('sample')->leaf('.*','product_id')->bind('GET', 'product');
        $router->node('download')->leaf('\d+','file_id','int')->bind('GET', 'download');
        $router->node('math')->node('PI')->leaf('[0-9\.]+','pi','float')->bind('GET', 'math');
        $router->node('fruits')->leaf('.*','fruits[]')->bind('GET', 'fruits');
        
    }))->build();
    
    require_once $base_dir . '/demo/include/simple_routes.php';
    
    $end = microtime(true);
    echo 'time=' . round(($end-$start)*1000, 3) . 'msec' . PHP_EOL;
}
catch(Throwable $e)
{
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}










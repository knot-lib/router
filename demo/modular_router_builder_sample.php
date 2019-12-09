<?php
$base_dir = dirname(__DIR__);

require_once $base_dir . '/vendor/autoload.php';
require_once $base_dir . '/demo/autoloaders/autoloader.php';
require_once $base_dir . '/demo/autoloaders/module_autoloader.php';
require_once $base_dir . '/demo/include/global_handlers.php';

use KnotLib\Router\Builder\ModularRouterBuilder;
use KnotLib\Router\Router;
use Sample\DownloadRouterModule;
use Sample\FruitsRouterModule;
use Sample\HomeRouterModule;
use Sample\MathRouterModule;
use Sample\ProductRouterModule;

$start = microtime(true);

try{
    $router = (new ModularRouterBuilder(
        new Router('global_dispatcher'),
        [
            new DownloadRouterModule,
            new FruitsRouterModule,
            new HomeRouterModule,
            new MathRouterModule,
            new ProductRouterModule
        ]
    ))->build();
}
catch(Throwable $e){
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}

require_once $base_dir . '/demo/include/simple_routes.php';

$end = microtime(true);
echo 'time=' . round(($end-$start)*1000, 3) . 'msec' . PHP_EOL;

<?php
$base_dir = dirname(__DIR__);

require_once $base_dir . '/vendor/autoload.php';
require_once $base_dir . '/demo/autoloaders/autoloader.php';
require_once $base_dir . '/demo/include/global_handlers.php';

use KnotLib\Router\Builder\CachedRouterBuilder;
use KnotLib\Router\Router;
use KnotLib\Cache\FileCache;
use KnotLib\Config\ConfigUtil;
use KnotLib\Config\ConfigReader;


$start = microtime(true);

try{
    $cache_config_file = __DIR__.'/config/cache.json';
    $reader = new ConfigReader([], function($keyword){
        
        return $keyword == 'SAMPLE_DIR' ? dirname(__DIR__) . '/demo' : null;
        
    });
    $cache = new FileCache(ConfigUtil::loadFromFile($cache_config_file, $reader));
    
    $routing_rules_file = __DIR__.'/config/routing_rules.json';
    $router = (new CachedRouterBuilder(new Router('global_dispatcher'), $cache, $routing_rules_file, new ConfigReader()))->build();
    
    require_once $base_dir . '/demo/include/simple_routes.php';
    
}
catch(Throwable $e){
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}

$end = microtime(true);
echo 'time=' . round(($end-$start)*1000, 3) . 'msec' . PHP_EOL;

<?php
spl_autoload_register(function ($class)
{
    switch($class){
        case 'Sample\DownloadRouterModule':
            require __DIR__ . '/../modules/DownloadRouterModule.php';
            break;
        case 'Sample\FruitsRouterModule':
            require __DIR__ . '/../modules/FruitsRouterModule.php';
            break;
        case 'Sample\HomeRouterModule':
            require __DIR__ . '/../modules/HomeRouterModule.php';
            break;
        case 'Sample\MathRouterModule':
            require __DIR__ . '/../modules/MathRouterModule.php';
            break;
        case 'Sample\ProductRouterModule':
            require __DIR__ . '/../modules/ProductRouterModule.php';
            break;
    }
});

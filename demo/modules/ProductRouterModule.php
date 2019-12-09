<?php
namespace Sample;

use KnotLib\Router\RouterInterface;
use KnotLib\Router\RouterModuleInterface;

class ProductRouterModule implements RouterModuleInterface
{
    /**
     * Install routing rule(s) into router
     *
     * @param RouterInterface $router
     */
    public function install(RouterInterface $router)
    {
        $router->node('product')->node('sample')->leaf('.*','product_id')->bind('GET', 'product');
    }
}
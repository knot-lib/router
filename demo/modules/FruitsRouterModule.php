<?php
namespace Sample;

use KnotLib\Router\RouterInterface;
use KnotLib\Router\RouterModuleInterface;

class FruitsRouterModule implements RouterModuleInterface
{
    /**
     * Install routing rule(s) into router
     *
     * @param RouterInterface $router
     */
    public function install(RouterInterface $router)
    {
        $router->node('fruits')->leaf('.*','fruits[]')->bind('GET', 'fruits');
    }
}
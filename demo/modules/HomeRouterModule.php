<?php
namespace Sample;

use KnotLib\Router\RouterInterface;
use KnotLib\Router\RouterModuleInterface;

class HomeRouterModule implements RouterModuleInterface
{
    /**
     * Install routing rule(s) into router
     *
     * @param RouterInterface $router
     */
    public function install(RouterInterface $router)
    {
        $router->leaf('()')->bind('GET', 'home');
    }
}
<?php
namespace Sample;

use KnotLib\Router\RouterInterface;
use KnotLib\Router\RouterModuleInterface;

class MathRouterModule implements RouterModuleInterface
{
    /**
     * Install routing rule(s) into router
     *
     * @param RouterInterface $router
     */
    public function install(RouterInterface $router)
    {
        $router->node('math')->node('PI')->leaf('[0-9\.]+','pi','float')->bind('GET', 'math');
    }
}
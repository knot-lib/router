<?php
declare(strict_types=1);

namespace KnotLib\Router;


interface RouterModuleInterface
{
    /**
     * Install routing rule(s) into router
     *
     * @param RouterInterface $router
     */
    public function install(RouterInterface $router);
}
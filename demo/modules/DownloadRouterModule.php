<?php
namespace Sample;

use KnotLib\Router\RouterInterface;
use KnotLib\Router\RouterModuleInterface;

class DownloadRouterModule implements RouterModuleInterface
{
    /**
     * Install routing rule(s) into router
     *
     * @param RouterInterface $router
     */
    public function install(RouterInterface $router)
    {
        $router->node('download')->leaf('\d+','file_id','int')->bind('GET', 'download');
    }
}
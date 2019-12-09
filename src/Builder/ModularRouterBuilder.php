<?php
declare(strict_types=1);

namespace KnotLib\Router\Builder;

use Throwable;

use KnotLib\Router\Exception\RouterBuildingException;
use KnotLib\Router\RouterModuleInterface;
use KnotLib\Router\RouterInterface;

class ModularRouterBuilder extends AbstractRouterBuilder
{
    /** @var RouterModuleInterface[] */
    private $modules;
    
    /**
     * PhpArrayRouterBuilder constructor.
     *
     * @param RouterInterface $router
     * @param RouterModuleInterface[] $modules
     */
    public function __construct(RouterInterface $router, array $modules)
    {
        parent::__construct($router);
        $this->modules = $modules;
    }
    
    /**
     * Build a router
     *
     * @return RouterInterface
     *
     * @throws RouterBuildingException
     */
    public function build() : RouterInterface
    {
        $router = $this->router;
        try{
            foreach($this->modules as $module)
            {
                $module->install($router);
            }
        }
        catch(Throwable $e)
        {
            throw new RouterBuildingException('Failed to build route', 0, $e);
        }
        return $router;
    }
    
}
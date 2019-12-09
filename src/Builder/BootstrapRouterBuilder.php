<?php
declare(strict_types=1);

namespace KnotLib\Router\Builder;

use Throwable;

use KnotLib\Router\Exception\RouterBuildingException;
use KnotLib\Router\RouterInterface;

class BootstrapRouterBuilder extends AbstractRouterBuilder
{
    /** @var callable */
    private $bootstrap;
    
    /**
     * BootstrapRouterBuilder constructor.
     *
     * @param RouterInterface $router
     * @param callable $bootstrap
     */
    public function __construct(RouterInterface $router, callable $bootstrap)
    {
        parent::__construct($router);
        $this->bootstrap = $bootstrap;
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
            // call bootstrap code
            $startup = $this->bootstrap;
            
            $startup($router);
        }
        catch(Throwable $e)
        {
            throw new RouterBuildingException('Failed to build route', 0, $e);
        }
        return $router;
    }
    
}
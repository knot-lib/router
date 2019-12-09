<?php
declare(strict_types=1);

namespace KnotLib\Router\Builder;

use KnotLib\Router\RouterBuilderInterface;
use KnotLib\Router\RouterInterface;

abstract class AbstractRouterBuilder implements RouterBuilderInterface
{
    /** @var RouterInterface */
    protected $router;

    /**
     * AbstractRouterBuilder constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
}
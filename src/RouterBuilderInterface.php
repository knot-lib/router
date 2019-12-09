<?php
declare(strict_types=1);

namespace KnotLib\Router;

use KnotLib\Router\Exception\RouterBuildingException;

interface RouterBuilderInterface
{
    /**
     * Build a router
     *
     * @return RouterInterface
     *
     * @throws RouterBuildingException
     */
    public function build() : RouterInterface;
}
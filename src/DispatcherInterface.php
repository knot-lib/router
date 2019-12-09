<?php
declare(strict_types=1);

namespace KnotLib\Router;

interface DispatcherInterface
{
    /**
     * Dispatch event
     *
     * @param string $path
     * @param array $vars
     * @param string $route_name
     */
    public function dispatch(string $path, array $vars, string $route_name);
}
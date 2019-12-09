<?php
declare(strict_types=1);

namespace KnotLib\Router;

interface RoutedCallbackInterface
{
    /**
     * Callback when routed
     *
     * @param string $path
     * @param array $vars
     * @param string $route_name
     *
     * @return mixed
     */
    public function routed(string $path, array $vars, string $route_name = null);
}
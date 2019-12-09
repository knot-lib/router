<?php
declare(strict_types=1);

namespace KnotLib\Router;

interface RouteNodeInterface
{
    /**
     * check if node satisfies path condition
     *
     * @param string $component
     * @param array &$vars
     * @param string &$next_path
     *
     * @return bool
     */
    public function match(string $component, array &$vars, string &$next_path) : bool;
    
    /**
     * Find child node
     *
     * @param RouterInterface $router
     * @param string $path
     * @param string $component
     * @param string $filter
     * @param array &$vars
     * @param RoutedCallbackInterface|callable|null $callback
     *
     * @return bool
     */
    public function route(RouterInterface $router, string $path, string $component, string $filter, array &$vars, $callback = null) : bool;
    
    /**
     * Add node
     *
     * @param string $path_spec
     * @param string $varname
     * @param string $type
     *
     * @return RouteNodeInterface
     */
    public function node(string $path_spec, string $varname = null, string $type = null) : RouteNodeInterface;
    
    /**
     * Add leaf node
     *
     * @param string $path_spec
     * @param string $varname
     * @param string $type
     *
     * @return RouteNodeInterface
     */
    public function leaf(string $path_spec, string $varname = null, string $type = null) : RouteNodeInterface;
    
    /**
     * Bind event
     *
     * @param string $filter
     * @param string $route_name
     * @param RoutedCallbackInterface|callable $callback
     */
    public function bind(string $filter, string $route_name, $callback = null);
}
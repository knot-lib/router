<?php
declare(strict_types=1);

namespace KnotLib\Router;

use KnotLib\Router\Exception\RoutingRuleBindingException;

interface RouterInterface
{
    /**
     * Bind rule
     *
     * @param string $routing_rule
     * @param string $filter
     * @param string $route_name
     * @param RoutedCallbackInterface|callable $callback
     *
     * @return RouterInterface
     *
     * @throws RoutingRuleBindingException
     */
    public function bind(string $routing_rule, string $filter, string $route_name, $callback = null) : RouterInterface;

    /**
     * Bind node
     *
     * @param string $path_spec
     * @param string $varname
     * @param string $type
     *
     * @return RouteNodeInterface
     */
    public function node(string $path_spec, string $varname = null, string $type = null) : RouteNodeInterface;

    /**
     * Bind leaf node
     *
     * @param string $path_spec
     * @param string $varname
     * @param string $type
     *
     * @return RouteNodeInterface
     */
    public function leaf(string $path_spec, string $varname = null, string $type = null) : RouteNodeInterface;

    /**
     * Route path
     *
     * filter - '*' means all filter passes
     *
     * @param string $path
     * @param string $filter
     * @param RoutedCallbackInterface|callable|null $callback
     */
    public function route(string $path, string $filter, $callback = null);

    /**
     * Dispatch event
     *
     * @param string $path
     * @param array $vars
     * @param string $route_name
     */
    public function dispatch(string $path, array $vars, string $route_name);

    /**
     * Set not found callback
     *
     * @param RoutedCallbackInterface|callable $not_found_callback
     *
     * @return RouterInterface
     */
    public function notFound($not_found_callback = null) : RouterInterface;
}
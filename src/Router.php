<?php
declare(strict_types=1);

namespace KnotLib\Router;

use Closure;

use KnotLib\Router\Exception\RouterNodeBuilderException;
use KnotLib\Router\Exception\RoutingRuleBindingException;
use KnotLib\Router\Util\RouterNodeBuilder;
use KnotLib\Router\Node\RootNode;

class Router implements RouterInterface
{
    const ROUTE_NOT_FOUND   = 'not_found';
    
    /** @var callable|DispatcherInterface */
    private $dispatcher;
    
    /** @var RootNode */
    private $root_node;

    /** @var callable */
    private $not_found_callback;

    /**
     * Router constructor.
     *
     * @param callback|Closure|DispatcherInterface $dispatcher
     */
    public function __construct($dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
        $this->root_node = new RootNode();
    }

    /**
     * Set not found callback
     *
     * @param RoutedCallbackInterface|callable $not_found_callback
     *
     * @return RouterInterface
     */
    public function notFound($not_found_callback = null) : RouterInterface
    {
        $this->not_found_callback = $not_found_callback;
        return $this;
    }
    
    /**
     * Dispatch event
     *
     * @param string $path
     * @param array $vars
     * @param string $route_name
     */
    public function dispatch(string $path, array $vars, string $route_name)
    {
        $dispatcher = $this->dispatcher;
        if ($dispatcher){
            if (is_callable($dispatcher)){
                $dispatcher($path, $vars, $route_name);
            }
            else if ($dispatcher instanceof DispatcherInterface){
                $dispatcher->dispatch($path, $vars, $route_name);
            }
        }
        if($route_name === self::ROUTE_NOT_FOUND && $this->not_found_callback){
            ($this->not_found_callback)($path, $vars, self::ROUTE_NOT_FOUND);
        }
    }

    /**
     * Get root node
     *
     * @return RootNode
     */
    public function getRoot() : RootNode
    {
        return $this->root_node;
    }
    
    /**
     * Get root node
     *
     * @param RootNode $root
     */
    public function replaceRoot(RootNode $root)
    {
        $this->root_node = $root;
    }

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
    public function bind(string $routing_rule, string $filter, string $route_name, $callback = null) : RouterInterface
    {
        $builder = new RouterNodeBuilder($routing_rule, $filter, $route_name, $callback);
        try{
            $builder->build($this->root_node);
        }
        catch(RouterNodeBuilderException $e){
            throw new RoutingRuleBindingException($routing_rule, 'Failed to build route.', 0, $e);
        }
        return $this;
    }
    
    /**
     * Route path
     *
     * filter - '*' means all filter passes
     *
     * @param string $path
     * @param string $filter
     * @param RoutedCallbackInterface|callable|null $callback
     *
     * @return bool
     */
    public function route(string $path, string $filter, $callback = null) : bool
    {
        $vars = [];
        if ($this->root_node->route($this, $path, $path, $filter, $vars, $callback))
        {
            return true;
        }

        // Notify EVENT_NOT_FOUND to callback
        if ($callback){
            if ($callback instanceof RoutedCallbackInterface){
                $callback->routed($path, $vars, self::ROUTE_NOT_FOUND);
            }
            else if (is_callable($callback)){
                ($callback)($path, $vars, self::ROUTE_NOT_FOUND);
            }
        }

        // dispatch event
        $this->dispatch($path, $vars, self::ROUTE_NOT_FOUND);

        return false;
    }
    
    /**
     * Bind node
     *
     * @param string $path_spec
     * @param string $varname
     * @param string $type
     *
     * @return RouteNodeInterface
     */
    public function node(string $path_spec, string $varname = null, string $type = null) : RouteNodeInterface
    {
        return $this->root_node->node( $path_spec, $varname, $type );
    }
    
    /**
     * Bind leaf node
     *
     * @param string $path_spec
     * @param string $varname
     * @param string $type
     *
     * @return RouteNodeInterface
     */
    public function leaf(string $path_spec, string $varname = null, string $type = null) : RouteNodeInterface
    {
        return $this->root_node->leaf($path_spec, $varname, $type);
    }
}
<?php
declare(strict_types=1);

namespace KnotLib\Router\Util;

use KnotLib\Router\Exception\NodeOperationException;
use KnotLib\Router\Node\RouteNode;
use KnotLib\Router\RoutedCallbackInterface;
use KnotLib\Router\RouteNodeInterface;
use KnotLib\Router\Node\LeafNode;
use KnotLib\Router\RouterInterface;

trait ParentNodeTrait
{
    /**
     * Returns children
     *
     * @return RouteNodeInterface[]
     */
    abstract public function getChildren() : array;
    
    /**
     * Update children
     *
     * @param array $children
     */
    abstract public function setChildren(array $children);
    
    /**
     * Get node id
     *
     * @return string
     */
    abstract public function getNodeId();

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
        $children = $this->getChildren();
        $node = $children[$path_spec] ?? null;
        if (!$node){
            $child_node_id = $this->getNodeId() . '/' . $path_spec;
            $node = new RouteNode($child_node_id, $path_spec, $varname, $type);
            $children[$path_spec] = $node;
            $this->setChildren($children);
        }
        return $node;
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
        $children = $this->getChildren();
        $node = $children[$path_spec] ?? null;
        if (!$node){
            $child_node_id = $this->getNodeId() . '/' . $path_spec;
            $node = new LeafNode($child_node_id, $path_spec, $varname, $type);
            $children[$path_spec] = $node;
            $this->setChildren($children);
        }
        return $node;
    }

    /**
     * Bind event
     *
     * @param string $filter
     * @param string $route_name
     * @param RoutedCallbackInterface|callable $callback
     *
     * @throws NodeOperationException
     */
    public function bind(/** @noinspection PhpUnusedParameterInspection */string $filter, string $route_name, $callback = null)
    {
        throw new NodeOperationException('Binding event');
    }
    
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
    public function route(RouterInterface $router, string $path, string $component, string $filter, array &$vars, $callback = null) : bool
    {
        $children = $this->getChildren();
        foreach($children as $child) {
            $next = '';
            $res = $child->match($component, $vars, $next);
            if ($res){
                if ($child->route($router, $path, $next, $filter, $vars, $callback)){
                    return true;
                }
            }
        }
        return false;
    }
    
}
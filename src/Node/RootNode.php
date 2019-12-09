<?php
declare(strict_types=1);

namespace KnotLib\Router\Node;

use KnotLib\Router\RoutedCallbackInterface;
use KnotLib\Router\Util\ParentNodeTrait;
use KnotLib\Router\RouteNodeInterface;
use KnotLib\Router\RouterInterface;

class RootNode implements RouteNodeInterface
{
    use ParentNodeTrait{
        route as protected traitRoute;
        leaf as protected traitLeaf;
    }
    
    /** @var RouteNode[] */
    private $children = [];

    /**
     * Check if no child exists
     *
     * @return bool
     */
    public function isEmpty() : bool
    {
        return empty($this->children);
    }
    
    /**
     * Returns children
     *
     * @return RouteNodeInterface[]
     */
    public function getChildren() : array
    {
        return $this->children;
    }
    
    /**
     * Update children
     *
     * @param array $children
     */
    public function setChildren(array $children)
    {
        $this->children = $children;
    }
    
    /**
     * Get node id
     *
     * @return string
     */
    public function getNodeId()
    {
        return '/';
    }

    /**
     * Add leaf node
     *
     * @param string $path_spec
     * @param string $varname
     * @param string $type
     *
     * @return RouteNodeInterface
     */
    public function leaf(string $path_spec, string $varname = null, string $type = null) : RouteNodeInterface
    {
        return $this->traitLeaf($path_spec, $varname, $type);
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
        return $this->traitRoute($router, $path, $component, $filter, $vars, $callback);
    }
    
    /**
     * check if node satisfies path condition
     *
     * @param string $path
     * @param array &$vars
     * @param string &$next_path
     *
     * @return bool
     */
    public function match(string $path, array &$vars, string &$next_path) : bool
    {
        return false;
    }
    
}
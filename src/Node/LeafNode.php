<?php
declare(strict_types=1);

namespace KnotLib\Router\Node;

use KnotLib\Router\RoutedCallbackInterface;
use KnotLib\Router\RouterInterface;

class LeafNode extends RouteNode
{
    //const FILTER_KEY_DEFAULT   = 'default';
    
    /** @var string[] */
    private $filters;

    /** @var RoutedCallbackInterface|callable */
    private $callback;

    /**
     * RouterLeafNode constructor.
     *
     * @param string $node_id
     * @param string $regex
     * @param string $varname
     * @param string $type
     */
    public function __construct(string $node_id, string $regex, string $varname = null, string $type = null)
    {
        parent::__construct($node_id, $regex, $varname, $type);
        
        $this->filters = [
            //self::FILTER_KEY_DEFAULT => Router::ROUTE_NOT_FOUND
        ];
    }
    
    /**
     * Return filters
     *
     * @return array
     */
    public function getFilters() : array
    {
        return $this->filters;
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
        // dispatch if no paths remain in buffer(means last node)
        if (empty($component)){
            if (empty($filter)){
                $event = $this->filters['*'] ?? '';
            }
            else{
                $event = $this->filters[$filter] ?? $this->filters['*'] ?? '';
                /*
                if(!$event){
                    foreach($this->filters as $filter => $e){
                        if ($filter === self::FILTER_KEY_DEFAULT){
                            $event = $e;
                        }
                    }
                }
                */
            }
            if (!empty($event)){
                // Call node callback
                if ($this->callback){
                    if ($this->callback instanceof RoutedCallbackInterface){
                        $this->callback->routed($path, $vars, $event);
                    }
                    else if (is_callable($this->callback)){
                        ($this->callback)($path, $vars, $event);
                    }
                }

                // Call method callback
                if ($callback){
                    if ($callback instanceof RoutedCallbackInterface){
                        $callback->routed($path, $vars, $event);
                    }
                    else if (is_callable($callback)){
                        ($callback)($path, $vars, $event);
                    }
                }

                // dispatch event
                $router->dispatch($path, $vars, $event);

                return true;
            }
        }
        return parent::route($router, $path, $component, $filter, $vars);
    }
    
    /**
     * Bind event to node
     *
     * @param string $filter
     * @param string $route_name
     * @param RoutedCallbackInterface|callable $callback
     */
    public function bind(string $filter, string $route_name, $callback = null)
    {
        $this->filters[$filter] = $route_name;
        $this->callback = $callback;
    }
}
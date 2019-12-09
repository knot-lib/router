<?php
declare(strict_types=1);

namespace KnotLib\Router\Builder;

use KnotLib\Router\Exception\RouterBuildingException;
use KnotLib\Router\Exception\RoutingRuleBindingException;
use KnotLib\Router\RouterInterface;

class PhpArrayRouterBuilder extends AbstractRouterBuilder
{
    /** @var array */
    private $routing_rules;
    
    /**
     * PhpArrayRouterBuilder constructor.
     *
     * @param RouterInterface $router
     * @param array $routing_rules
     */
    public function __construct(RouterInterface $router, array $routing_rules)
    {
        parent::__construct($router);
        $this->routing_rules = $routing_rules;
    }
    
    /**
     * Build a router
     *
     * @return RouterInterface
     *
     * @throws RouterBuildingException
     */
    public function build() : RouterInterface
    {
        $router = $this->router;
        foreach($this->routing_rules as $routing_rule => $events)
        {
            if (is_string($events)){
                try{
                    $router->bind($routing_rule, '*', $events);
                }
                catch(RoutingRuleBindingException $e)
                {
                    throw new RouterBuildingException('Rule binding failed:' . $routing_rule . ' event:' . $events, 0, $e);
                }
            }
            else if (is_array($events)){
                foreach($events as $filter => $event){
                    try{
                        $router->bind($routing_rule, $filter, $event);
                    }
                    catch(RoutingRuleBindingException $e)
                    {
                        throw new RouterBuildingException('Rule binding failed:' . $routing_rule . ' event:' . $event . ' filter:' . $filter, 0, $e);
                    }
                }
            }
        }
        return $router;
    }
    
}
<?php
declare(strict_types=1);

namespace KnotLib\Router\Builder;

use Throwable;

use KnotLib\Config\ConfigUtil;
use KnotLib\Config\ConfigReader;
use KnotLib\Cache\CacheInterface;
use KnotLib\Router\Exception\RouterBuildingException;
use KnotLib\Router\RouterInterface;

class CachedRouterBuilder extends AbstractRouterBuilder
{
    const ROUTING_CACHE_KEY = 'calgamo.router.routing';
    
    /** @var array */
    private $routing_rules;
    
    /**
     * CachedRouterFactory constructor.
     *
     * @param RouterInterface $router
     * @param CacheInterface $cache
     * @param string $config_file
     * @param ConfigReader $reader
     *
     * @throws
     */
    public function __construct(RouterInterface $router, CacheInterface $cache, string $config_file, ConfigReader $reader)
    {
        parent::__construct($router);
        $this->routing_rules = $cache->get(self::ROUTING_CACHE_KEY);
        if (!is_array($this->routing_rules)){
            $this->routing_rules = ConfigUtil::loadFromFile($config_file, $reader);
            $cache->set(self::ROUTING_CACHE_KEY, $this->routing_rules);
        }
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
        try{
            foreach($this->routing_rules as $routing_rule => $events)
            {
                if (is_string($events)){
                    $router->bind( $routing_rule, $events, '*' );
                }
                else if (is_array($events)){
                    foreach($events as $filter => $event){
                        $router->bind($routing_rule, $filter, $event);
                    }
                }
            }
        }
        catch(Throwable $e)
        {
            throw new RouterBuildingException('Failed to build route', 0, $e);
        }
        return $router;
    }
    
}
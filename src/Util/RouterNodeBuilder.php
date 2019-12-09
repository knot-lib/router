<?php
declare(strict_types=1);

namespace KnotLib\Router\Util;

use KnotLib\Router\Exception\RouterNodeBuilderException;
use KnotLib\Router\Exception\RoutingRuleParseException;
use KnotLib\Router\Node\RootNode;
use KnotLib\Router\Parser\RoutingRuleParser;
use KnotLib\Router\Parser\EmptyRoutingPathInterface;
use KnotLib\Router\RoutedCallbackInterface;

class RouterNodeBuilder
{
    /** @var string */
    private $routing_rule;

    /** @var string */
    private $filter;

    /** @var string */
    private $route_name;

    /** @var RoutedCallbackInterface|callable */
    private $callback;

    /**
     * RouterBuilder constructor.
     *
     * @param string $routing_rule
     * @param string $filter
     * @param string $route_name
     * @param RoutedCallbackInterface|callable $callback
     */
    public function __construct(string $routing_rule, string $filter, string $route_name, $callback = null)
    {
        $this->routing_rule = $routing_rule;
        $this->filter = $filter;
        $this->route_name = $route_name;
        $this->callback = $callback;
    }
    
    /**
     * Build router node tree
     *
     * @param RootNode $root
     *
     * @throws RouterNodeBuilderException
     */
    public function build(RootNode &$root)
    {
        $parser = new RoutingRuleParser($this->routing_rule);
        
        try{
            $section_list_set = $parser->parse();
        }
        catch(RoutingRuleParseException $e){
            throw new RouterNodeBuilderException($this->routing_rule, 'Parse failed');
        }
        
        $node = $root;
        
        foreach($section_list_set as $section_list){
            while(($section = array_shift($section_list))!==null) {
                $last = empty($section_list);
                if (is_string($section)){
                    if ($last){
                        $node->leaf($section)->bind($this->filter, $this->route_name, $this->callback);
                        break;
                    }
                    $node = $node->node($section);
                }
                else if (is_array($section)){
                    $varname = $section['varname'] ?? null;
                    $regex = $section['regex'] ?? null;
                    $type = $section['type'] ?? null;
                    $regex = empty($regex) ? '[\x20-\x7F]+' : $regex;
                    if ($last){
                        $node->leaf($regex,$varname,$type)->bind($this->filter, $this->route_name, $this->callback);
                        break;
                    }
                    $node = $node->node($regex,$varname,$type);
                }
                else if ($section instanceof EmptyRoutingPathInterface){
                    if ($last){
                        $node->leaf('()')->bind($this->filter, $this->route_name, $this->callback);
                        break;
                    }
                    throw new RouterNodeBuilderException($this->routing_rule, 'Empty section not allowed before last section');
                }
                else{
                    throw new RouterNodeBuilderException($this->routing_rule, 'invalid section');
                }
            }
        }
    }
}
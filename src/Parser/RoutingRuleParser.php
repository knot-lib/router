<?php
declare(strict_types=1);

namespace KnotLib\Router\Parser;

use KnotLib\Router\Exception\RoutingRuleParseException;

class RoutingRuleParser
{
    /** @var string */
    private $routing_rule;
    
    /**
     * RoutingParser constructor.
     *
     * @param string $routing_rule
     */
    public function __construct(string $routing_rule)
    {
        $this->routing_rule = $routing_rule;
    }
    
    /**
     * Parse routing rule
     *
     * @return array
     *
     * @throws RoutingRuleParseException
     */
    public function parse() : array
    {
        $buffer = $this->routing_rule;
        
        $section_list_set = [];
        $current_list = [];
        do
        {
            $pos = strpos(substr($buffer,1),'/');
            $section = $pos !== false ? substr($buffer, 1, $pos) : substr($buffer, 1);
            $buffer = $pos !== false ? substr($buffer, $pos + 1) : '';
            
            $length = strlen($section);
            if ($length === 0){
                $current_list[] = new class implements EmptyRoutingPathInterface {};
                if (!empty($buffer)){
                    throw new RoutingRuleParseException($this->routing_rule, "Empty routing section is allowed only for last element");
                }
                break;
            }
            $first = $section[0];
            $last = $section[$length - 1];
            if ($first === '[' && $last === ']'){
                // push current list
                $section_list_set[] = $current_list + ['()'];
                // add optional part to current list
                $inside = substr($section, 1, $length - 2);
                if ($inside[0] === ':') {
                    $current_list[] = self::parseVariablePattern($inside);
                }
                else {
                    $current_list[] = $inside;
                }
            }
            else if ($first === ':') {
                $current_list[] = self::parseVariablePattern($section);
            }
            else {
                $current_list[] = $section;
            }
        }
        while(!empty($buffer));
        
        $section_list_set[] = $current_list;
        return $section_list_set;
    }
    
    /**
     * Parse variable pattern
     *
     * @param string $patern
     *
     * @return array
     *
     * @throws RoutingRuleParseException
     */
    private static function parseVariablePattern(string $patern) : array
    {
        static $supported_types = [
            'string',
            'integer',
            'int',
            'float',
            'double',
            'boolean',
            'bool',
        ];
    
        $tmp = explode(':',$patern);
        $varname = $tmp[1] ?? null;
        $regex   = $tmp[2] ?? null;
        $type    = $tmp[3] ?? null;
        
        if (!preg_match('/^[a-zA-Z_][0-9a-zA-Z_]*(\[\])?$/',$varname)){
            throw new RoutingRuleParseException($patern, "illegal variable name: $varname");
        }
        if ($regex && !preg_match('/^[0-9a-zA-Z@#_=%\\\^\$\.\[\]\|\(\)\?\*\+\{\}\^\-]*$/',$regex)){
            throw new RoutingRuleParseException($patern, "illegal variable regex pattern: $regex");
        }
        if ($type && !in_array($type,$supported_types)){
            throw new RoutingRuleParseException($patern, "unsupported variable type: $type");
        }
        
        $res = [];
        
        if ($varname){
            $res['varname'] = $varname;
        }
        if ($regex){
            $res['regex'] = $regex;
        }
        if ($type){
            $res['type'] = $type;
        }
    
        return $res;
    }
}
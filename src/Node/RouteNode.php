<?php
declare(strict_types=1);

namespace KnotLib\Router\Node;

use KnotLib\Router\RoutedCallbackInterface;
use KnotLib\Router\Util\ParentNodeTrait;
use KnotLib\Router\RouterInterface;
use KnotLib\Router\RouteNodeInterface;

class RouteNode implements RouteNodeInterface
{
    use ParentNodeTrait{
        route as protected traitRoute;
        leaf as protected traitLeaf;
    }
    
    /** @var string */
    protected $node_id;
    
    /** @var string */
    private $regex;
    
    /** @var string */
    private $varname;
    
    /** @var string */
    private $type;
    
    /** @var RouteNode[] */
    private $children = [];

    /**
     * NodeRouter constructor.
     *
     * @param string $node_id
     * @param string $regex
     * @param string $varname
     * @param string $type
     */
    public function __construct(string $node_id, string $regex, string $varname = null, string $type = null)
    {
        $this->node_id = $node_id;
        $this->regex = $regex;
        $this->varname = $varname;
        $this->type = $type;
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
     * Returns regex
     *
     * @return string
     */
    public function getRegex() : string
    {
        return $this->regex;
    }
    
    /**
     * Returns var name
     *
     * @return string|null
     */
    public function getVarName()
    {
        return $this->varname;
    }
    
    /**
     * Returns type
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Get node id
     *
     * @return string
     */
    public function getNodeId()
    {
        return $this->node_id;
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
     * @param string $component
     * @param array &$vars
     * @param string &$next_path
     *
     * @return bool
     */
    public function match(string $component, array &$vars, string &$next_path) : bool
    {
        // parse query string: /path?foo=?bar&baz=qux
        $pos = strpos($component,'?');
        if ($pos !== false){
            $query = substr($component, $pos + 1);
            $component = substr($component, 0, $pos);
            parse_str($query, $auery_vars);
            $vars = array_replace($vars, $auery_vars);
        }
        // get first path section
        $pos = strpos(substr($component,1), '/');
        $first = $pos > 0 ? substr($component, 0, $pos + 1) : substr($component, 0);
        $next_path = $pos > 0 ? substr($component, $pos + 1) : '';
        // parse pattern
        $pattern = "@^/({$this->regex})$@";
        if (!preg_match($pattern, $first, $match) ){
            return false;
        }
        $varname = $this->varname;
        if (!empty($varname)){
            static $type_converters = [
                'string' => 'strval',
                'integer' => 'intval',
                'int' => 'intval',
                'float' => 'floatval',
                'double' => 'floatval',
                'boolean' => 'boolval',
                'bool' => 'boolval',
            ];
            $type_converter = $type_converters[$this->type] ?? null;
            $value = $type_converter ? ($type_converter)($match[1]) : $match[1];
            
            if (strpos($varname,'[]',strlen($varname)-3) !== false){
                $varname = substr($this->varname,0,-2);
                $vars[$varname][] = $value;
                $length = strlen($match[1]) + 1;
                $next = substr($component, $length);
                $pos = strpos(substr($next,1), '/');
                $next = substr($next, 0, $pos + 1);
                while(strlen($next)> 0 && preg_match($pattern, $next, $match))
                {
                    $value = $type_converter ? ($type_converter)($match[1]) : $match[1];
                    $vars[$varname][] = $value;
                    $length += strlen($match[1]) + 1;
                    $next = substr($component, $length);
                    $substr = strlen($next) >= 1 ? substr($next,1) : '';
                    $pos = strpos($substr, '/');
                    $next = $pos > 0 ? substr($next, 0, $pos + 1) : substr($next, 0);
                }
                $next_path = $next;
            }
            else{
                $vars[$this->varname] = $value;
            }
        }
        return true;
    }

}
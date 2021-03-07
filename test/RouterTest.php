<?php
/** @noinspection PhpUnusedParameterInspection */
declare(strict_types=1);

namespace KnotLib\Router\Test;

use Throwable;
use PHPUnit\Framework\TestCase;

use KnotLib\Router\Router;
use KnotLib\Router\DispatcherInterface;
use KnotLib\Router\Node\RootNode;
use KnotLib\Router\Node\LeafNode;
use KnotLib\Router\Exception\RoutingRuleBindingException;
use KnotLib\Router\RoutedCallbackInterface;

class MyDispatcher implements DispatcherInterface
{
    private $path;
    private $vars;
    private $route_name;
    public function dispatch(string $path, array $vars, string $route_name) : void
    {
        $this->path = $path;
        $this->vars = $vars;
        $this->route_name = $route_name;
    }
    public function clear()
    {
        $this->route_name = null;
        $this->vars = [];
    }
    public function getPath()
    {
        return $this->path;
    }
    public function getDispatchedVars()
    {
        return $this->vars;
    }
    public function getDispatchedRouteName()
    {
        return $this->route_name;
    }
}

class RouterTest extends TestCase
{
    public function testDispatch()
    {
        try{
            $ok = false;
            $router = new Router(
                function(string $path, array $vars, string $route_name) use(&$ok){
                    $ok = true;
                    return true;
                }
            );
            $router->bind('/path','GET','my_route');
        
            $this->assertEquals(false, $ok);
            $router->dispatch('/path',[],'my_route');
            $this->assertEquals(true, $ok);
    
            $dispatcher = new MyDispatcher();
            $router = new Router($dispatcher);
            $router->bind('/path','GET','my_route');
            
            $this->assertNull($dispatcher->getDispatchedRouteName());
            $router->dispatch('/path',[],'my_route');
            $this->assertEquals('my_route', $dispatcher->getDispatchedRouteName());
        }
        catch(Throwable $e)
        {
            $this->fail($e->getMessage());
        }
    }
    public function testGetRoot()
    {
        $router = new Router();
    
        $this->assertNotNull($router->getRoot());
        $this->assertInstanceOf(RootNode::class, $router->getRoot());
    }
    public function testReplaceRoot()
    {
        $router = new Router();
        
        $rootNodeHash = spl_object_hash($router->getRoot());

        $newRootNode = new RootNode();
        $newRootNodeHash = spl_object_hash($newRootNode);
    
        $router->replaceRoot($newRootNode);
    
        $this->assertEquals($newRootNodeHash, spl_object_hash($router->getRoot()));
        $this->assertNotEquals($rootNodeHash, spl_object_hash($router->getRoot()));
    }

    public function testBind()
    {
        $router = new Router();

        try{
            $router->bind('/path','GET','my_route');

            $root_node = $router->getRoot();

            $this->assertEquals(false, $root_node->isEmpty());
            $this->assertEquals(1, count($root_node->getChildren()));

            /** @var LeafNode $child_node */
            $child_node = $root_node->getChildren()['path'] ?? null;

            $this->assertInstanceOf(LeafNode::class, $child_node);
            $this->assertEquals(['GET' =>'my_route'], $child_node->getFilters());
            $this->assertEquals('//path', $child_node->getNodeId());
            $this->assertEquals('path', $child_node->getRegex());
            $this->assertNull($child_node->getVarName());
            $this->assertNull($child_node->getType());
            $this->assertEmpty($child_node->getChildren());

            $router->bind('/path?foo=bar&baz=qux','GET','my_route');

        }
        catch(RoutingRuleBindingException $e)
        {
            $this->fail();
        }

        try {
            $router->bind('/path','GET','my_route');
            $this->assertTrue(true);
        }
        catch(RoutingRuleBindingException $e)
        {
            $this->fail();
        }
    }
    
    /**
     * @throws RoutingRuleBindingException
     */
    public function testRoute()
    {
        $dispatcher = new MyDispatcher();
        $router = new Router($dispatcher);
        $router->bind('/path','GET','apple');
        $router->bind('/path/subpath','GET','banana');
    
        $this->assertNull($dispatcher->getDispatchedRouteName());
        $router->route('/path','GET');
        $this->assertEquals('apple', $dispatcher->getDispatchedRouteName());
        $router->route('/path/subpath','GET');
        $this->assertEquals('banana', $dispatcher->getDispatchedRouteName());

        // home routing
        $dispatcher = new MyDispatcher();
        $router = new Router($dispatcher);

        $router->bind('/','GET','home');
        $router->route('/','GET');
        $this->assertEquals('home', $dispatcher->getDispatchedRouteName());

        // child node routing
        $dispatcher = new MyDispatcher();
        $router = new Router($dispatcher);

        $router->bind('/child','GET','a');
        $router->route('/child','GET');
        $this->assertEquals('a', $dispatcher->getDispatchedRouteName());

        $dispatcher = new MyDispatcher();
        $router = new Router($dispatcher);

        $router->bind('/child/:var','GET','a');
        $router->bind('/child/:var','POST','b');
        $router->bind('/child/','GET','c');

        $router->route('/child/','GET');
        $this->assertEquals('c', $dispatcher->getDispatchedRouteName());

        $dispatcher->clear();
        $router->route('/child/123','GET');
        $this->assertEquals('a', $dispatcher->getDispatchedRouteName());
        $this->assertEquals(['var'=>'123'], $dispatcher->getDispatchedVars());

        $dispatcher->clear();
        $router->route('/child/123','POST');
        $this->assertEquals('b', $dispatcher->getDispatchedRouteName());
        $this->assertEquals(['var'=>'123'], $dispatcher->getDispatchedVars());

        // optional routing
        $dispatcher = new MyDispatcher();
        $router = new Router($dispatcher);

        $router->bind('/[optional]','GET','home');
        $router->route('/','GET');
        $this->assertEquals('home', $dispatcher->getDispatchedRouteName());
        $dispatcher->clear();
        $router->route('/optional','GET');
        $this->assertEquals('home', $dispatcher->getDispatchedRouteName());
        
        // query string
        $dispatcher = new MyDispatcher();
        $router = new Router($dispatcher);

        $router->bind('/path','GET','my_route');

        $router->route('/path?foo=bar&baz=qux','GET');

        $vars = $dispatcher->getDispatchedVars();
        $this->assertEquals(['foo'=>'bar','baz'=>'qux'], $vars);

        $dispatcher = new MyDispatcher();
        $router = new Router($dispatcher);

        $router->bind('/path/','GET','my_route');

        $router->route('/path/?foo=bar&baz=qux','GET');

        $vars = $dispatcher->getDispatchedVars();
        $this->assertEquals(['foo'=>'bar','baz'=>'qux'], $vars);

        // callback
        $router = new Router();

        $router->bind('/path/','GET','my_route');

        $router->route('/path/?foo=bar&baz=qux','GET', function($path, $vars, $event){
            $this->assertEquals('/path/?foo=bar&baz=qux', $path);
            $this->assertEquals('my_route', $event);
            $this->assertEquals(['foo'=>'bar','baz'=>'qux'], $vars);
        });

        $router->route('/path/?foo=bar&baz=qux','GET',
            new class($this) implements RoutedCallbackInterface
            {
                private $test;
                public function __construct(TestCase $test)
                {
                    $this->test = $test;
                }
                public function routed(string $path, array $vars, string $route_name = null)
                {
                    $this->test->assertEquals('/path/?foo=bar&baz=qux', $path);
                    $this->test->assertEquals('my_route', $route_name);
                    $this->test->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $vars);
                }
            }
        );
    }


    /**
     * @throws
     */
    public function testRoute2()
    {
        $dispatcher = new MyDispatcher();
        $router = new Router($dispatcher);

        $router->bind('/child/','GET','c');

        $router->route('/child/','GET');
        $this->assertEquals('c', $dispatcher->getDispatchedRouteName());
        $this->assertEquals([], $dispatcher->getDispatchedVars());
    }
}
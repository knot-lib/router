<?php
declare(strict_types=1);

namespace KnotLib\Router\Test;

use PHPUnit\Framework\TestCase;
use KnotLib\Router\Node\RouteNode;

class RouteNodeTest extends TestCase
{
    public function testMatch()
    {
        $vars = [];
        $path = '';

        $node = new RouteNode('','()');
    
        $this->assertEquals(true, $node->match('/',$vars, $path));
        $this->assertEquals(false, $node->match('/abc',$vars, $path));
        
        $node = new RouteNode('','abc');
    
        $this->assertEquals(false, $node->match('/',$vars, $path));
        $this->assertEquals(true, $node->match('/abc',$vars, $path));
    
        $node = new RouteNode('','[a|b]');
    
        $this->assertEquals(false, $node->match('/',$vars, $path));
        $this->assertEquals(true, $node->match('/a',$vars, $path));
        $this->assertEquals(true, $node->match('/b',$vars, $path));
        $this->assertEquals(false, $node->match('/c',$vars, $path));
    
        $node = new RouteNode('','.*key');
    
        $this->assertEquals(true, $node->match('/key',$vars, $path));
        $this->assertEquals(true, $node->match('/monkey',$vars, $path));
        $this->assertEquals(false, $node->match('/lion',$vars, $path));
        $this->assertEquals(false, $node->match('/keyholder',$vars, $path));
    
        $node = new RouteNode('','[0-9\.]+','num','int');
    
        $this->assertEquals(true, $node->match('/1',$vars, $path));
        $this->assertEquals(1, $vars['num']);
        $this->assertEquals(true, $node->match('/12',$vars, $path));
        $this->assertEquals(12, $vars['num']);
        $this->assertEquals(true, $node->match('/3.4',$vars, $path));
        $this->assertEquals(3, $vars['num']);
        $this->assertEquals(false, $node->match('/apple',$vars, $path));
    
        $node = new RouteNode('','[0-9\.]+','num','float');
    
        $this->assertEquals(true, $node->match('/1',$vars, $path));
        $this->assertEquals(1, $vars['num']);
        $this->assertEquals(true, $node->match('/12',$vars, $path));
        $this->assertEquals(12, $vars['num']);
        $this->assertEquals(true, $node->match('/3.4',$vars, $path));
        $this->assertEquals(3.4, $vars['num']);
        $this->assertEquals(false, $node->match('/apple',$vars, $path));
    }
    
}
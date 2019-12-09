<?php
declare(strict_types=1);

namespace KnotLib\Router\Test;

use PHPUnit\Framework\TestCase;

use KnotLib\Router\Exception\RoutingRuleParseException;
use KnotLib\Router\Parser\RoutingRuleParser;
use KnotLib\Router\Parser\EmptyRoutingPathInterface;

class RoutingRuleParserTest extends TestCase
{
    /**
     * @throws
     */
    public function testParse()
    {
        // root only
        $parser = new RoutingRuleParser('/');
        $result = $parser->parse();

        $this->assertEquals(1, count($result));
        $this->assertEquals(1, count($result[0]));
        $this->assertInstanceOf(EmptyRoutingPathInterface::class, $result[0][0]);

        // trailing slash
        $parser = new RoutingRuleParser('/test/');
        $result = $parser->parse();

        $this->assertEquals(1, count($result));
        $this->assertEquals(2, count($result[0]));
        $this->assertEquals('test', $result[0][0]);
        $this->assertInstanceOf(EmptyRoutingPathInterface::class, $result[0][1]);

        // static route
        $parser = new RoutingRuleParser('/test');
        $result = $parser->parse();

        $this->assertEquals([['test']], $result);

        // variable
        $parser = new RoutingRuleParser('/test/:user_id');
        $result = $parser->parse();

        $rule = ['test',['varname'=>'user_id']];
        $this->assertEquals([$rule], $result);

        $parser = new RoutingRuleParser('/test/:user_id[]');
        $result = $parser->parse();

        $rule = ['test',['varname'=>'user_id[]']];
        $this->assertEquals([$rule], $result);

        // variable with regex
        $parser = new RoutingRuleParser('/test/:user_id:\d+');
        $result = $parser->parse();

        $rule = ['test',['varname'=>'user_id','regex'=>'\d+']];
        $this->assertEquals([$rule], $result);

        // variable with regex and type
        $parser = new RoutingRuleParser('/test/:user_id:\d+:int');
        $result = $parser->parse();

        $rule = ['test',['varname'=>'user_id','regex'=>'\d+','type'=>'int']];
        $this->assertEquals([$rule], $result);

        // colon(:) included path
        try{
            $parser = new RoutingRuleParser('/aaa:bbb');
            $parser->parse();
            $this->assertTrue(true);
        }
        catch(RoutingRuleParseException $e){
            $this->fail();
        }

        // optional path
        $parser = new RoutingRuleParser('/test/[optional]');
        $result = $parser->parse();

        $rule1 = ['test'];
        $rule2 = ['test', 'optional'];
        $this->assertEquals([$rule1, $rule2], $result);

        // optional variable
        $parser = new RoutingRuleParser('/test/[:user_id]');
        $result = $parser->parse();

        $rule1 = ['test'];
        $rule2 = ['test', ['varname'=>'user_id']];
        $this->assertEquals([$rule1, $rule2], $result);

        // optional variable with regex
        $parser = new RoutingRuleParser('/test/[:user_id:\d+]');
        $result = $parser->parse();

        $rule1 = ['test'];
        $rule2 = ['test', ['varname'=>'user_id','regex'=>'\d+']];
        $this->assertEquals([$rule1, $rule2], $result);

        // optional variable with regex and type
        $parser = new RoutingRuleParser('/test/[:user_id:\d+:int]');
        $result = $parser->parse();

        $rule1 = ['test'];
        $rule2 = ['test', ['varname'=>'user_id','regex'=>'\d+','type'=>'int']];
        $this->assertEquals([$rule1, $rule2], $result);
    }
    
    public function testParseError()
    {
        // invalid variable name: fist character must be alphabet
        try{
            $parser = new RoutingRuleParser('/:9aa');
            $parser->parse();
            $this->fail();
        }
        catch(RoutingRuleParseException $e){
            $this->assertTrue(true);
        }
    
        // invalid variable name: hyphen
        try{
            $parser = new RoutingRuleParser('/:a-b');
            $parser->parse();
            $this->fail();
        }
        catch(RoutingRuleParseException $e){
            $this->assertTrue(true);
        }
    
        // invalid variable name: period
        try{
            $parser = new RoutingRuleParser('/:a.b');
            $parser->parse();
            $this->fail();
        }
        catch(RoutingRuleParseException $e){
            $this->assertTrue(true);
        }
    
        // invalid regex
        try{
            $parser = new RoutingRuleParser('/:abc:~');
            $parser->parse();
            $this->fail();
        }
        catch(RoutingRuleParseException $e){
            $this->assertTrue(true);
        }
    
        // invalid type: array
        try{
            $parser = new RoutingRuleParser('/:abc:def:array');
            $parser->parse();
            $this->fail();
        }
        catch(RoutingRuleParseException $e){
            $this->assertTrue(true);
        }
    
        // invalid type: object
        try{
            $parser = new RoutingRuleParser('/:abc:def:object');
            $parser->parse();
            $this->fail();
        }
        catch(RoutingRuleParseException $e){
            $this->assertTrue(true);
        }
        
        // empty path
        try{
            $parser = new RoutingRuleParser('//');
            $parser->parse();
            $this->fail();
        }
        catch(RoutingRuleParseException $e){
            $this->assertTrue(true);
        }
    
        // empty path before last section
        try{
            $parser = new RoutingRuleParser('//test');
            $parser->parse();
            $this->fail();
        }
        catch(RoutingRuleParseException $e){
            $this->assertTrue(true);
        }
    
    }
}
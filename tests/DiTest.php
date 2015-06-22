<?php
/**
 * Tests for SugiPHP Sugi Class
 *
 * @package SugiPHP.Sugi
 * @license http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use PHPUnit_Framework_TestCase;
use SugiPHP\Sugi\App;

class DiTest extends PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $app = App::getInstance();
        $foo = $app->make('SugiPHP\Sugi\Foo', ['config' => [1, 2, 3]]);
        $this->assertInstanceOf("SugiPHP\Sugi\Foo", $foo);
        $this->assertEquals([1, 2, 3], $foo->config);
        $this->assertNull($foo->bar->config);

        $foo = $app->make('SugiPHP\Sugi\Foo', ['SugiPHP\Sugi\Bar' => ['config' => [1, 2, 3]]]);
        $this->assertEquals([1, 2, 3], $foo->bar->config);
        $this->assertNull($foo->config);
    }

    public function testCall()
    {
        $app = App::getInstance();
        $res = $app->call('SugiPHP\Sugi\Foo', 'test');
        $this->assertTrue($res);
    }

    public function testCall2()
    {
        $app = App::getInstance();
        $res = $app->call('SugiPHP\Sugi\Foo', 'test2', ['someparam' => 'someParamValue']);
        $this->assertTrue($res);
    }

    public function testCallApp()
    {
        $app  = App::getInstance();
        $app2 = $app->call('SugiPHP\Sugi\Foo', 'getApp');
        $this->assertSame($app, $app2);
    }

    public function testAlias()
    {
        $app = App::getInstance();
        $app->setAlias('SugiPHP\Sugi\Bar', 'SugiPHP\Sugi\Test');
        $bar = $app->make('SugiPHP\Sugi\Bar');
        $this->assertInstanceOf('SugiPHP\Sugi\Test', $bar);
    }

}

class Foo
{

    public $config;
    public $bar;

    public function __construct(Bar $bar, App $app, $config, $str = 'test')
    {
        $this->bar    = $bar;
        $this->app    = $app;
        $this->config = $config;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function test(Test $test)
    {
        $this->bar->test();
        $test->test();
        // echo "\nHello from Foo::test !";
        return true;
    }

    public function test2($someparam)
    {
        return true;
    }

}

class Bar
{
    public $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function test()
    {
        // echo "\nHello from Bar::test !";
    }
}

class Test
{
    public function test()
    {
        // echo "\nHello from Test::test !";
    }

}

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
        $foo = $app->make('SugiPHP\Sugi\Foo');
        $this->assertInstanceOf("SugiPHP\Sugi\Foo", $foo);
    }

    public function testCall()
    {
        $app = App::getInstance();
        $res = $app->call('SugiPHP\Sugi\Foo', 'test');
        $this->assertTrue($res);
    }

}

class Foo
{
    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }

    public function test(Test $test)
    {
        $this->bar->test();
        $test->test();
        echo "\nHello from Foo::test !";
        return true;
    }

}

class Bar
{
    public function test()
    {
        echo "\nHello from Bar::test !";
    }
}

class Test
{
    public function test()
    {
        echo "\nHello from Test::test !";
    }

}

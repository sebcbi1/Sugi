<?php
/**
 * Application's Dependency Injection.
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

trait DITrait
{

    public function make($class)
    {
        $reflector        = new \ReflectionClass($class);
        $reflectionMethod = $reflector->getConstructor();
        $parameters       = $this->getParameters($reflectionMethod);
        return $reflector->newInstanceArgs($parameters);
    }

    public function call($class, $method)
    {
        $obj              = $this->make($class);
        $reflectionMethod = new \ReflectionMethod($class, $method);
        $parameters       = $this->getParameters($reflectionMethod);
        return $reflectionMethod->invokeArgs($obj, $parameters);
    }

    protected function getParameters($reflectionMethod)
    {
        $parameters = [];
        if ($reflectionMethod) {
            $dependencies = $reflectionMethod->getParameters();
            if ($dependencies) {
                foreach ($dependencies as $dependency) {
                    $parameters[] = $this->make($dependency->getClass()->name);
                }
            }
        }
        return $parameters;
    }

}

<?php
/**
 * Application's Dependency Injection.
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

trait DITrait
{

    public function make($class, $parameters = [])
    {
        if ($class == 'SugiPHP\Sugi\App') {
            return $this;
        }

        $reflector        = new \ReflectionClass($class);
        $arguments        = $this->getArguments($reflector->getConstructor(), $parameters);
        return $reflector->newInstanceArgs($arguments);
    }

    public function call($class, $method, $parameters = [])
    {
        $obj              = $this->make($class, isset($parameters[$class]) ? $parameters[$class] : []);
        $reflectionMethod = new \ReflectionMethod($class, $method);
        $arguments        = $this->getArguments($reflectionMethod, $parameters);
        return $reflectionMethod->invokeArgs($obj, $arguments);
    }

    protected function getArguments($reflectionMethod, $parameters = [])
    {
        $arguments = [];
        if ($reflectionMethod) {
            $dependencies = $reflectionMethod->getParameters();
            if ($dependencies) {
                foreach ($dependencies as $dependency) {
                    if ($class = $dependency->getClass()) {
                        $class = $class->name;
                        $arguments[] = $this->make($class,isset($parameters[$class]) ? $parameters[$class] : [] );
                    } elseif (isset($parameters[$dependency->name])) {
                        $arguments[] = $parameters[$dependency->name];
                    } elseif ($dependency->isDefaultValueAvailable()) {
                        $arguments[] = $dependency->getDefaultValue();
                    } else {
                        $arguments[] = null;
                    }
                }
            }
        }
        return $arguments;
    }

}

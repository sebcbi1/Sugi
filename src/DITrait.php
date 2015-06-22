<?php
/**
 * Application's Dependency Injection.
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

trait DITrait
{

    protected $aliases = [];

    public function setAlias($abstract = null, $concrete = null)
    {
        if ($abstract && $concrete && !isset($this->aliases[$abstract])) {
            $this->aliases[$abstract] = $concrete;
        }
    }

    public function setAliases($aliases = [])
    {
        foreach ($aliases as $abstract => $concrete) {
            $this->setAlias($abstract, $concrete);
        }
    }

    public function getConcrete($abstract)
    {
        if (isset($this->aliases[$abstract])) {
            return $this->aliases[$abstract];
        }
        return $abstract;
    }

    public function make($class, $parameters = [])
    {
        $class = $this->getConcrete($class);

        if ($class == 'SugiPHP\Sugi\App') {
            return $this;
        }
        $reflector = new \ReflectionClass($class);
        $arguments = $this->getArguments($reflector->getConstructor(), $parameters);
        return $reflector->newInstanceArgs($arguments);
    }

    public function call($class, $method, $parameters = [])
    {
        $obj              = $this->make($class, isset($parameters[$class]) ? $parameters[$class] : []);
        $class            = $this->getConcrete($class);
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
                        $class       = $class->name;
                        $arguments[] = $this->make($class, isset($parameters[$class]) ? $parameters[$class] : []);
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

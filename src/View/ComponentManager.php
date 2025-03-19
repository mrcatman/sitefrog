<?php

namespace Sitefrog\View;

use ReflectionClass;

class ComponentManager
{
    private $components = [];

    public function register($name, $component)
    {
        $this->components[$name] = $component;
    }

    public function get($name)
    {
        if (!isset($this->components[$name])) {
            throw new \Exception("Component \"$name\" not found");
        }
        return $this->components[$name];
    }

    public function makeInstance($name, $data)
    {
        $class = $this->get($name);
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return new $class;
        }

        $params = $constructor->getParameters();
        $constructorArgs = [];

        foreach ($params as $param) {
            if (isset($data[$param->getName()])) {
                $constructorArgs[$param->getName()] = $data[$param->getName()];
            } else {
                $constructorArgs[$param->getName()] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
            }
        }
        return $reflection->newInstanceArgs($constructorArgs);
    }

}

<?php
namespace Sitefrog\Traits;

use Illuminate\Support\Str;

trait MagicGetSet
{
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'set')) {
            $key = Str::camel(substr($method, 3, strlen($method)));

            $value = $parameters[0];
            if (!array_key_exists($key, get_class_vars($this::class))) {
                throw new \BadMethodCallException(sprintf(
                    'Undefined property: %s::%s', static::class, $key
                ));
            }
            $this->$key = $value;
            return $this;
        }

        if (Str::startsWith($method, 'get')) {
            $key = Str::camel(substr($method, 3, strlen($method)));
            return $this->$key;
        }
        throw new \BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}

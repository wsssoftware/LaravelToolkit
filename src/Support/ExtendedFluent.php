<?php

namespace LaravelToolkit\Support;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Fluent;
use ReflectionClass;
use ReflectionMethod;

abstract class ExtendedFluent extends Fluent
{
    private function getAttribute($offset): ?Attribute
    {
        $offset = str($offset)->camel()->toString();
        $reflection = new ReflectionClass($this);
        $method = $reflection->hasMethod($offset) ? $reflection->getMethod($offset) : null;
        if (
            $method instanceof ReflectionMethod &&
            $method->isProtected() &&
            $method->getReturnType()?->getName() === Attribute::class &&
            $method->getNumberOfParameters() === 0
        ) {
            return rescue(fn () => $method->invoke($this), null, false);
        }

        return null;
    }

    public function offsetSet($offset, $value): void
    {
        if ($attribute = $this->getAttribute($offset)) {
            $callable = $attribute->set;
            $value = $callable($value);
        }
        parent::offsetSet($offset, $value);
    }

    public function value($key, $default = null): mixed
    {
        $value = parent::value($key, $default);
        if ($attribute = $this->getAttribute($key)) {
            $callable = $attribute->get;
            $value = $callable($value);
        }

        return $value;
    }
}

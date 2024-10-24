<?php

namespace LaravelToolkit\ACL;

interface DenyStatusCode
{

    /**
     * Http status code on not allowed
     */
    public function denyStatus(): int;
}

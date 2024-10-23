<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static null|class-string<\LaravelToolkit\ACL\UserPermission> model()
 * @method static null|array permissions():
 * @method static void withModel(string $model)
 *
 * @see \LaravelToolkit\ACL\ACL
 */
class ACL extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\ACL\ACL::class;
    }
}

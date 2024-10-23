<?php

namespace LaravelToolkit\Facades;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Facade;
use LaravelToolkit\ACL\Format;
use LaravelToolkit\ACL\UserPermission;

/**
 * @method static null|array gatePermissions()
 * @method static null|class-string<\LaravelToolkit\ACL\UserPermission> model()
 * @method static null|array permissions(Format $format = Format::COMPLETE, User $user = null):
 * @method static null|UserPermission userPermission(?User $user = null)
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

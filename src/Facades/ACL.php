<?php

namespace LaravelToolkit\Facades;

use Closure;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Facade;
use LaravelToolkit\ACL\Format;
use LaravelToolkit\ACL\UserPermission;

/**
 * @method static null|string rolesEnum()
 * @method static null|array gatePermissions()
 * @method static null|string model()
 * @method static null|array permissions(Format $format = Format::COMPLETE, ?Closure $filter = null, User $user = null):
 * @method static null|UserPermission userPermission(?User $user = null)
 * @method static \LaravelToolkit\ACL\ACL withModel(string $model)
 * @method static \LaravelToolkit\ACL\ACL withRolesEnum(string $enum)
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

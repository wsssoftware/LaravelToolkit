<?php

namespace LaravelToolkit\ACL;

use Exception;

class ACL
{
    protected static null|string $model = null;

    public static function withModel(string $model): void
    {
        throw_if(!is_subclass_of($model, UserPermission::class), Exception::class, 'Model must extends UserPermission');
        self::$model = $model;
    }

    public static function model(): ?string
    {
        return self::$model;
    }
}

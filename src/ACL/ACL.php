<?php

namespace LaravelToolkit\ACL;

use Closure;
use Exception;
use Illuminate\Foundation\Auth\User;

class ACL
{
    protected readonly string $model;

    public function gatePermissions(): null|array
    {
        return $this->permissions(Format::ONLY_VALUES);
    }

    /**
     * @return class-string<\LaravelToolkit\ACL\UserPermission>
     */
    public function model(): ?string
    {
        return $this->model ?? null;
    }

    public function permissions(Format $format = Format::COMPLETE, ?Closure $filter = null, User $user = null): null|array
    {
        $userPermission = $this->userPermission($user);
        if ($userPermission === null) {
            return null;
        }

        return $userPermission->permissions($format, $filter);

    }

    public function userPermission(?User $user = null): ?UserPermission
    {
        return ($user ?? auth()->user())?->userPermission;
    }

    public function withModel(string $model): void
    {
        throw_if(!is_subclass_of($model, UserPermission::class), Exception::class, 'Model must extends UserPermission');
        $this->model = $model;
    }
}

<?php

namespace LaravelToolkit\ACL;

use Exception;
use Illuminate\Foundation\Auth\User;

class ACL
{
    protected readonly string $model;

    /**
     * @return class-string<\LaravelToolkit\ACL\UserPermission>
     */
    public function model(): ?string
    {
        return $this->model ?? null;
    }

    public function withModel(string $model): void
    {
        throw_if(!is_subclass_of($model, UserPermission::class), Exception::class, 'Model must extends UserPermission');
        $this->model = $model;
    }

    public function userPermission(User $user = null): UserPermission
    {
        return ($user ?? auth()->user())->userPermission;
    }

    public function permissions(): null|array
    {
        $model = $this->model();
        $userId = auth()->id();
        if ($model === null || $userId === null) {
            return null;
        }
        $userPermission = self::model()::query()->where('id', $userId)->firstOrFail();

        return $userPermission->getPolicies()
            ->reduce(fn(array $carryPolicy, Policy $policy) => $carryPolicy + $policy->rules->reduce(fn(
                    array $carryRules,
                    Rule $rule
                ) => $carryRules + [
                        "$policy->column::$rule->key" => $userPermission->{$policy->column}->{$rule->key}->value,
                    ], []), []);

    }
}

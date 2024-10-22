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

    /**
     * @return class-string<\LaravelToolkit\ACL\UserPermission>
     */
    public static function model(): ?string
    {
        return self::$model;
    }

    public static function permissions(): null|array
    {
        $model = self::model();
        $userId = auth()->id();
        if ($model === null || $userId === null) {
            return null;
        }
        $userPermission = self::model()::query()->where('user_id', $userId)->firstOrFail();

        return $userPermission::getPolicies()
            ->reduce(fn(array $carryPolicy, Policy $policy) => $carryPolicy + $policy->rules->reduce(fn(
                    array $carryRules,
                    Rule $rule
                ) => $carryRules + [
                        "$policy->column::$rule->key" => $userPermission->{$policy->column}->{$rule->key}->value,
                    ], []), []);

    }
}

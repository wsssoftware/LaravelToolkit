<?php

namespace LaravelToolkit\ACL;

use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use LaravelToolkit\Facades\ACL;

class PolicyCast implements CastsAttributes
{

    /**
     * Cast the given value.
     *
     * @param  \LaravelToolkit\ACL\UserPermission  $model
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (json_validate($value)) {
            $value = json_decode($value, true);
        }
        $values = $value ?? [];
        $policy = $model->getPolicies($key);
        $policy->rules->each(fn(Rule $rule) => $rule->setValue(Arr::get($values, $rule->key, false)));
        return $policy;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \LaravelToolkit\ACL\UserPermission  $model
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $policy = $model->getPolicies();
        if ($value instanceof Policy) {
            foreach ($value->rules as $rule) {
                $policy->{$rule->key}->value = $rule->value;
            }
        } elseif (is_array($value)) {
            foreach ($value as $ruleKey => $permission) {
                throw_if(!is_string($ruleKey), Exception::class, 'Key must be a string.');
                throw_if(!is_bool($permission), Exception::class, 'Value must be a boolean.');
                $policy->{$ruleKey}->value = $permission;
            }
        }

        return $policy->rules->filter(fn(Rule $rule) => $rule->value !== null)
            ->mapWithKeys(fn(Rule $rule) => [$rule->key => $rule->value])
            ->toJson();
    }
}

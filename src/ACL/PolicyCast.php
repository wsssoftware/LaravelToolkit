<?php

namespace LaravelToolkit\ACL;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class PolicyCast implements CastsAttributes
{

    public function __construct(
        public readonly Policy $policy,
    ) {
        //
    }

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $values = $value ?? [];
        $this->policy->setColumn($key);
        $this->policy->rules->each(fn(Rule $rule) => $rule->setValue(Arr::get($values, $rule->key, false)));
        return $this->policy;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->policy->rules->filter(fn(Rule $rule) => $rule->value !== null)
            ->mapWithKeys(fn(Rule $rule) => [$rule->key => $rule->value])
            ->toJson();
    }
}

<?php

namespace LaravelToolkit\ACL;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property \LaravelToolkit\ACL\UserPermission $resource
 */
class CompleteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->resource->getPolicies()
            ->sortBy('name')
            ->reduce(fn(Collection $c, Policy $p) => $c->merge($this->reducePolicy($p)), collect())
            ->values()
            ->toArray();
    }

    protected function reducePolicy(Policy $policy): Collection
    {
        return $policy->rules->reduce(fn(Collection $c, Rule $r) => $c->merge($this->reduceRule($policy, $r)), collect());
    }

    protected function reduceRule(Policy $policy, Rule $rule): array
    {
        return ["$policy->column::$rule->key" => [
            'id' => "$policy->column::$rule->key",
            'policy_column' => $policy->column,
            'policy_name' => $policy->name,
            'policy_description' => $policy->description,
            'rule_key' => $rule->key,
            'rule_name' => $rule->name,
            'rule_description' => $rule->description,
            'rule_deny_status' => $rule->denyStatus,
            'rule_value' => $this->{$policy->column}->{$rule->key}->value,
        ]];
    }
}

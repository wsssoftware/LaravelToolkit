<?php

namespace LaravelToolkit\ACL;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return $this->resource->getPolicies()->map(fn(Policy $policy) => [
            'column' => $policy->column,
            'name' => $policy->name,
            'description' => $policy->description,
            'rules' => $policy->rules->map(fn(Rule $rule) => [
                'key' => $rule->key,
                'name' => $rule->name,
                'description' => $rule->description,
                'deny_status' => $rule->denyStatus,
                'value' => $this->{$policy->column}->{$rule->key}->value,
            ])->values()->toArray(),
        ])->values()->toArray();
    }
}

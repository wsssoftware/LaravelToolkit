<?php

namespace LaravelToolkit\ACL;

use BackedEnum;
use Exception;
use LaravelToolkit\Facades\ACL;

trait ManagementTrait
{

    private function startup(): void
    {
        $this->policies = collect();
        $this->declarePoliciesAndRoles();
        $this->policies = $this->policies
            ->map(fn(Policy|PolicyMaker $p) => $p instanceof PolicyMaker ? $p->toPolicy() : $p);
        $this->fillable[] = 'id';
        $this->fillable[] = 'roles';
        foreach ($this->policies as $policy) {
            $this->fillable[] = $policy->column;
        }
        $this->fillable[] = 'updated_at';
    }

    protected function registryPolicy(string $column, string $name, string $description): PolicyMaker
    {
        $this->policies->put($column, new PolicyMaker(collect(), $column, $name, $description));
        return $this->policies->get($column);
    }

    public function fillPolicies(array $policies): void
    {
        foreach ($policies as $policy => $value) {
            [$policy, $rule] = explode('::', $policy);
            $this->{$policy}->{$rule}->value = $value;
        }
    }

    public function denyAll(string $policy = null): void
    {
        $policies = $policy !== null ? [$this->policies->get($policy)] : $this->policies;
        foreach ($policies as $policy) {
            foreach ($policy->rules as $rule) {
                $this->{$policy->column} = $policy->{$rule->key}->value = false;
            }
        }
    }

    public function deny(string $ability): void
    {
        [$policy, $rule] = explode('::', $ability);
        $this->{$policy} = $policy->{$rule}->value = false;
    }

    public function grantAll(string $policy = null): void
    {
        $policies = $policy !== null ? [$this->policies->get($policy)] : $this->policies;
        foreach ($policies as $policy) {
            foreach ($policy->rules as $rule) {
                $this->{$policy->column} = $policy->{$rule->key}->value = true;
            }
        }
    }

    public function grant(string $ability): void
    {
        [$policy, $rule] = explode('::', $ability);
        $this->{$policy} = $policy->{$rule}->value = true;
    }

    public function grantRole(BackedEnum $role): void
    {
        $enumType = ACL::rolesEnum();
        throw_if($enumType === null, Exception::class, 'You must configure RoleEnum before use it');
        throw_if(!$role instanceof $enumType, Exception::class, 'Enum must to be instance of '.$enumType);
        $collection = $this->roles ?? collect();
        $collection->put($role->value, $role);
        $this->roles = $collection;
    }

    public function grantAllRoles(): void
    {
        /** @var BackedEnum|null $enum */
        if ($enum = ACL::rolesEnum()) {
            $collection = collect();
            foreach ($enum::cases() as $case) {
                $collection->put($case->value, $case);
            }
            $this->roles = $collection;
        }
    }

    public function denyRole(BackedEnum $role): void
    {
        $this->roles = ($this->roles ?? collect())->filter(fn(BackedEnum $r) => $r->value !== $role->value);
    }

    public function denyAllRoles(): void
    {
        $this->roles = collect();
    }
}

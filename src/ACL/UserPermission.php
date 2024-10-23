<?php

namespace LaravelToolkit\ACL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method Policy __get(string $name)
 */
abstract class UserPermission extends Model
{

    /**
     * @var \Illuminate\Support\Collection<string, \LaravelToolkit\ACL\Policy>
     */
    private Collection $policies;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);
        $this->fillable[] = 'id';
        foreach (self::getPolicies() as $policy) {
            $this->fillable[] = $policy->column;
        }
        $this->fillable[] = 'created_at';
        $this->fillable[] = 'updated_at';
    }

    /**
     * This method declare policies for your ACL system
     */
    abstract protected function declarePolicies(): void;

    /**
     * @return \Illuminate\Support\Collection<string, \LaravelToolkit\ACL\Policy>|\LaravelToolkit\ACL\Policy
     */
    public function getPolicies(string $column = null): Collection|Policy
    {
        if (empty($this->policies)) {
            $this->policies = collect();
            $this->declarePolicies();
            $this->policies = $this->policies
                ->map(fn(Policy|PolicyMaker $p) => $p instanceof PolicyMaker ? $p->toPolicy() : $p);
        }
        foreach ($this->policies as $policy) {
            foreach ($policy->rules as $rule) {
                $policy->{$rule->key}->value = $this->{$policy->column}?->{$rule->key}->value ?? false;
            }
        }
        return !empty($column) ? $this->policies->get($column) : $this->policies;
    }

    protected function registryPolicy(string $column, string $name, ?string $description = null): PolicyMaker
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

    public function denyAll(): void
    {
        foreach (self::getPolicies() as $policy) {
            foreach ($policy->rules as $rule) {
               $this->{$policy->column} = $policy->{$rule->key}->value = false;
            }
        }
    }

    public function grantAll(): void
    {
        foreach (self::getPolicies() as $policy) {
            foreach ($policy->rules as $rule) {
                $this->{$policy->column} = $policy->{$rule->key}->value = true;
            }
        }
    }

    final public function casts(): array
    {
        $cast = ['id' => 'int'];
        foreach (self::getPolicies() as $policy) {
            $cast[$policy->column] = PolicyCast::class;
        }
        $cast['created_at'] = 'datetime';
        $cast['updated_at'] = 'datetime';
        return $cast;
    }

    public function toArray(): array
    {
        return self::getPolicies()->map(fn(Policy $policy) => [
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

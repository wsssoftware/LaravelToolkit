<?php

namespace LaravelToolkit\ACL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method Policy __get(string $name)
 */
abstract class UserPermission extends Model
{

    /**
     * @var \Illuminate\Support\Collection<string, \LaravelToolkit\ACL\Policy>
     */
    protected Collection $policies;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        $this->policies = collect();
        $this->declarePolicies();
        $this->policies = $this->policies
            ->map(fn(Policy|PolicyMaker $p) => $p instanceof PolicyMaker ? $p->toPolicy() : $p);
        $this->fillable[] = 'id';
        foreach ($this->policies as $policy) {
            $this->fillable[] = $policy->column;
        }
        $this->fillable[] = 'created_at';
        $this->fillable[] = 'updated_at';
        parent::__construct($attributes);
    }

    /**
     * This method declare policies for your ACL system
     */
    abstract protected function declarePolicies(): void;

    /**
     * @return \Illuminate\Support\Collection<string, \LaravelToolkit\ACL\Policy>
     */
    public function getPolicies(): Collection
    {
        $policies = collect();
        foreach ($this->policies as $policy) {
           $policies->put($policy->column, $this->{$policy->column});
        }
        return !empty($column) ? $policies->get($column) : $policies;
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

    public function denyAll(string $policy = null): void
    {
        $policies = $policy !== null ? [$this->policies->get($policy)] : $this->policies;
        foreach ($policies as $policy) {
            foreach ($policy->rules as $rule) {
               $this->{$policy->column} = $policy->{$rule->key}->value = false;
            }
        }
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

    final public function casts(): array
    {
        $cast = ['id' => 'int'];
        foreach ($this->policies as $policy) {
            $cast[$policy->column] = PolicyCast::class;
        }
        $cast['created_at'] = 'datetime';
        $cast['updated_at'] = 'datetime';
        return $cast;
    }

    public function permissions(Format $format = Format::COMPLETE): array
    {
        return match ($format) {
            Format::COMPLETE => CompleteResource::make($this)->resolve(),
            Format::ONLY_VALUES => OnlyValueResource::make($this)->resolve(),
        };
    }
}

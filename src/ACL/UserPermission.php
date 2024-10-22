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
    private static Collection $policies;

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

    abstract protected static function declarePolicies(): void;

    /**
     * @return \Illuminate\Support\Collection<string, \LaravelToolkit\ACL\Policy>|\LaravelToolkit\ACL\Policy
     */
    public static function getPolicies(string $column = null): Collection|Policy
    {
        if (empty(static::$policies)) {
            static::$policies = collect();
            static::declarePolicies();
            static::$policies = static::$policies
                ->map(fn(Policy|PolicyMaker $p) => $p instanceof PolicyMaker ? $p->toPolicy() : $p);
        }
        return !empty($column) ? static::$policies->get($column) :static::$policies;
    }

    protected static function registryPolicy(string $column, string $name, ?string $description = null): PolicyMaker
    {
        static::$policies->put($column, new PolicyMaker(collect(), $column, $name, $description));
        return static::$policies->get($column);
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
}

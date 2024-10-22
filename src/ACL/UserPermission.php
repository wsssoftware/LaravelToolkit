<?php

namespace LaravelToolkit\ACL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
abstract class UserPermission extends Model
{

    /**
     * @var \Illuminate\Support\Collection<string, \LaravelToolkit\ACL\Policy>
     */
    private readonly Collection $policies;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        $this->policies = collect();
        $this->declarePolicies();
        parent::__construct($attributes);
        $this->fillable[] = 'id';
        foreach ($this->policies as $policy) {
            $this->fillable[] = $policy->column;
        }
        $this->fillable[] = 'created_at';
        $this->fillable[] = 'updated_at';
    }

    abstract protected function declarePolicies(): void;

    protected function registryPolicy(string $column, string $name, ?string $description = null): Policy
    {
        $this->policies->put($column, new Policy($column, $name, $description));
        return $this->policies->get($column);
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
}

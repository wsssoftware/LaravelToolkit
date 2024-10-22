<?php

namespace LaravelToolkit\ACL;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

readonly class Policy implements Castable
{

    protected string $column;
    protected string $name;

    /**
     * @var \Illuminate\Support\Collection<string, Rule>
     */
    public Collection $rules;

    private function __construct(string $name)
    {
        $this->name = $name;
        $this->rules = collect();
    }

    public function setColumn(string $column): self
    {
        $this->column = $column;
        return $this;
    }

    public function rule(string $key, string $name, ?int $denyStatus = null): self
    {
        $this->rules->put($key, new Rule($key, $name, $denyStatus));
        return $this;
    }

    public function crud(?int $denyStatus = null): self
    {
        $this->rules->put('create', new Rule('create', 'laraveltoolkit::acl.create', $denyStatus));
        $this->rules->put('read', new Rule('read', 'laraveltoolkit::acl.read', $denyStatus));
        $this->rules->put('update', new Rule('update', 'laraveltoolkit::acl.update', $denyStatus));
        $this->rules->put('delete', new Rule('delete', 'laraveltoolkit::acl.delete', $denyStatus));
        return $this;
    }

    public function build(): string
    {
        throw_if($this->rules->isEmpty(), Exception::class, 'There are no rules.');;
        return sprintf(
            '%s:%s:%s',
            self::class,
            bin2hex($this->name),
            $this->rules->map(fn(Rule $rule) => $rule->serialize())
            ->implode(':')
        );
    }

    public static function create(string $name): self
    {
        return new static($name);
    }

    public static function castUsing(array $arguments): CastsAttributes
    {
        $policy = new static(hex2bin(array_shift($arguments)));
        foreach ($arguments as $argument) {
            $rule = Rule::unserialize($argument);
            $policy->rules->put($rule->key, $rule);
        }
        return new PolicyCast($policy);
    }
}

<?php

namespace LaravelToolkit\ACL;

use Illuminate\Support\Collection;

readonly class Policy
{


    /**
     * @var \Illuminate\Support\Collection<string, Rule>
     */
    public Collection $rules;

    public function __construct(
        public string $column,
        public string $name,
        public ?string $description = null,
    ) {
        $this->rules = collect();
    }

    public function rule(string $key, string $name, ?string $description = null, ?int $denyStatus = null): self
    {
        $this->rules->put($key, new Rule($key, $name, $description, $denyStatus));
        return $this;
    }

    public function crud(?int $denyStatus = null): self
    {
        $this->rules->put('create', new Rule('create', 'laraveltoolkit::acl.create.name', 'laraveltoolkit::acl.create.description', $denyStatus));
        $this->rules->put('read', new Rule('read', 'laraveltoolkit::acl.read.name', 'laraveltoolkit::acl.read.description', $denyStatus));
        $this->rules->put('update', new Rule('update', 'laraveltoolkit::acl.update.name', 'laraveltoolkit::acl.update.description', $denyStatus));
        $this->rules->put('delete', new Rule('delete', 'laraveltoolkit::acl.delete.name', 'laraveltoolkit::acl.delete.description', $denyStatus));
        return $this;
    }
}

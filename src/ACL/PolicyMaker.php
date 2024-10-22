<?php

namespace LaravelToolkit\ACL;

use Illuminate\Support\Collection;

class PolicyMaker
{
    public function __construct(
        public Collection $rules,
        public string $column,
        public string $name,
        public ?string $description = null,
    ) {
        //
    }

    public function rule(string $key, string $name, ?string $description = null, ?int $denyStatus = null): self
    {
        $this->rules->put($key, new Rule($key, $name, $description, $denyStatus));
        return $this;
    }

    public function crud(?int $denyStatus = null): self
    {
        $this->rules->put('create', new Rule(
            'create',
            __('laraveltoolkit::acl.create.name'),
            __('laraveltoolkit::acl.create.description', ['name' => $this->name]),
            $denyStatus
        ));
        $this->rules->put('read', new Rule(
            'read',
            __('laraveltoolkit::acl.read.name'),
            __('laraveltoolkit::acl.read.description', ['name' => $this->name]),
            $denyStatus
        ));
        $this->rules->put('update', new Rule(
            'update',
            __('laraveltoolkit::acl.update.name'),
            __('laraveltoolkit::acl.update.description', ['name' => $this->name]),
            $denyStatus
        ));
        $this->rules->put('delete', new Rule(
            'delete',
            __('laraveltoolkit::acl.delete.name'),
            __('laraveltoolkit::acl.delete.description', ['name' => $this->name]),
            $denyStatus
        ));
        return $this;
    }

    public function toPolicy(): Policy
    {
        return new Policy($this->rules, $this->column, $this->name, $this->description);
    }
}

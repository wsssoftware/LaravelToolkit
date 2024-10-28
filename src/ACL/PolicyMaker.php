<?php

namespace LaravelToolkit\ACL;

use Exception;
use Illuminate\Support\Collection;

class PolicyMaker
{
    public function __construct(
        public Collection $rules,
        public string $column,
        public string $name,
        public string $description,
    ) {
        throw_if(
            in_array($this->column, ['id', 'roles', 'created_at']),
            Exception::class,
            "$this->column is a reserved name and cannot used on key."
        );
    }

    public function rule(string $key, string $name, string $description, ?int $denyStatus = null): self
    {
        $this->rules->put($key, new Rule($key, $name, $description, $denyStatus));

        return $this;
    }

    public function create(?int $denyStatus = null): self
    {
        $this->rules->put('create', new Rule(
            'create',
            __('laraveltoolkit::acl.create.name'),
            __('laraveltoolkit::acl.create.description', ['name' => mb_strtolower($this->name)]),
            $denyStatus
        ));

        return $this;
    }

    public function crud(?int $denyStatus = null): self
    {
        $this->create($denyStatus);
        $this->read($denyStatus);
        $this->update($denyStatus);
        $this->delete($denyStatus);

        return $this;
    }

    public function delete(?int $denyStatus = null): self
    {
        $this->rules->put('delete', new Rule(
            'delete',
            __('laraveltoolkit::acl.delete.name'),
            __('laraveltoolkit::acl.delete.description', ['name' => mb_strtolower($this->name)]),
            $denyStatus
        ));

        return $this;
    }

    public function read(?int $denyStatus = null): self
    {
        $this->rules->put('read', new Rule(
            'read',
            __('laraveltoolkit::acl.read.name'),
            __('laraveltoolkit::acl.read.description', ['name' => mb_strtolower($this->name)]),
            $denyStatus
        ));

        return $this;
    }

    public function toPolicy(): Policy
    {
        return new Policy($this->rules, $this->column, $this->name, $this->description);
    }

    public function update(?int $denyStatus = null): self
    {
        $this->rules->put('update', new Rule(
            'update',
            __('laraveltoolkit::acl.update.name'),
            __('laraveltoolkit::acl.update.description', ['name' => mb_strtolower($this->name)]),
            $denyStatus
        ));

        return $this;
    }
}

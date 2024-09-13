<?php

namespace LaravelToolkit\Sitemap;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Sitemap
{
    protected Collection $closureRepositories;

    protected Collection $domains;

    protected Collection $groups;

    protected bool $locked = false;

    /**
     * @var \Illuminate\Support\Collection<int, Index|Url>
     */
    protected Collection $items;

    public function __construct()
    {
        $this->closureRepositories = collect();
        $this->domains = collect();
        $this->groups = collect();
        $this->items = collect();
    }

    public function addGroup(string $name): self
    {
        throw_if(
            $this->items->filter(fn (Index|Url $item) => ! $item instanceof Index)->isNotEmpty(),
            Exception::class,
            'You cannot combine indexes and url in same sitemap.'
        );
        $this->items->push(new Index($name));

        return $this;
    }

    public function addUrl(
        Url|string $url,
        ?Carbon $lastModified = null,
        ?ChangeFrequency $changeFrequency = null,
        ?float $priority = null
    ): self {
        throw_if(
            $this->items->filter(fn (Index|Url $item) => ! $item instanceof Url)->isNotEmpty(),
            Exception::class,
            'You cannot combine indexes and url in same sitemap.'
        );
        $this->items->push($url instanceof Url ? $url : new Url($url, $lastModified, $changeFrequency,
            $priority));

        return $this;
    }

    public function domain(string $name, Closure $closure): void
    {
        throw_if($this->locked, Exception::class, 'You cannot put a domain inside another domain.');
        throw_if($this->domains->offsetExists($name), Exception::class, "Domain '{$name}' already declared.");
        $this->domains->put($name, $closure);
    }

    public function domainExists(string $name): bool
    {
        return $this->domains->offsetExists($name);
    }

    public function fromQuery(Builder $builder, Closure $closure): self
    {
        throw_if(
            $this->items->filter(fn (Index|Url $item) => ! $item instanceof Url)->isNotEmpty(),
            Exception::class,
            'You cannot combine indexes and url in same sitemap.'
        );
        $this->closureRepositories->push(new ClosureRepository($builder, $closure));

        return $this;
    }

    public function fromCollection(Collection $collection, Closure $closure): self
    {
        throw_if(
            $this->items->filter(fn (Index|Url $item) => ! $item instanceof Url)->isNotEmpty(),
            Exception::class,
            'You cannot combine indexes and url in same sitemap.'
        );
        $this->closureRepositories->push(new ClosureRepository($collection, $closure));

        return $this;
    }

    public function group(string $group, Closure $closure): void
    {
        throw_if($this->locked, Exception::class, 'Groups must be placed on root of sitemap.php');
        throw_if($this->groups->offsetExists($group), Exception::class, "Group '{$group}' already declared.");
        $this->groups->put($group, $closure);
    }

    public function groupExists(?string $group): bool
    {
        return $group === null || $this->groups->offsetExists($group);
    }

    /**
     * @return \Illuminate\Support\Collection<int, Index,Url>
     */
    public function process(string $domain, ?string $group): Collection
    {
        return match (true) {
            ! empty($group) => $this->processGroup($group),
            $this->domainExists($domain) => $this->processDomain($domain),
            default => $this->processDefault()
        };
    }

    protected function processGroup(string $group): Collection
    {
        $this->items = collect();
        $this->closureRepositories = collect();
        $this->locked = true;
        $this->groups->get($group)->call($this);
        $this->resolveClosureRepositories();
        $this->locked = false;

        return $this->items;
    }

    protected function processDomain(string $domain): Collection
    {
        $this->items = collect();
        $this->closureRepositories = collect();
        $this->locked = true;
        $this->domains->get($domain)->call($this);
        $this->resolveClosureRepositories();
        $this->locked = false;

        return $this->items;
    }

    protected function processDefault(): Collection
    {
        $this->resolveClosureRepositories();

        return $this->items;
    }

    protected function resolveClosureRepositories(): void
    {
        $this->closureRepositories->each(fn (ClosureRepository $repository) => $repository->resolve());
    }
}

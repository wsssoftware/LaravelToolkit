<?php

namespace LaravelToolkit\Sitemap;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Sitemap
{
    protected Collection $urlSet;

    public function __construct(
        protected ?string $group = null,
    )
    {
        $this->urlSet = collect();
    }

    public function addUrl(
        Url|string $url,
        ?Carbon $lastModified = null,
        ?ChangeFrequency $changeFrequency = null,
        ?float $priority = null
    ): self {
        $this->urlSet->push($url instanceof Url ? $url : new Url($url, $lastModified, $changeFrequency, $priority));
        return $this;
    }

    public function fromQuery(Builder $builder, Closure $closure): self
    {
        $builder->each(fn (Model $model) => $closure->call($this, $model));
        return $this;
    }

    public function fromCollection(Collection $collection, Closure $closure): self
    {
        $collection->each(fn (mixed $item) => $closure->call($this, $item));
        return $this;
    }
}

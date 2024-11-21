<?php

namespace LaravelToolkit\DataAdapter;

use Illuminate\Support\Collection;

readonly class Filter
{

    private function __construct(
        public string $field,
        public Operator $operator,
        public Collection $constraints,
    ) {}

    /**
     * @return \Illuminate\Support\Collection<string, \LaravelToolkit\DataAdapter\Filter>|null
     */
    public static function create(?array $filters, string $globalFilterName): ?Collection
    {
        if (empty($filters)) {
            return null;
        }

        $collection = collect($filters)
            ->mapWithKeys(function (array $filter, string $key) use ($globalFilterName) {
                return [$key => self::createItem($key, $globalFilterName, $filter)];
            })
            ->filter(fn ($filter) => $filter instanceof Filter);

        return $collection->count() > 0 ? $collection : null;
    }

    protected static function createItem(string $field, string $globalFilterName, array $data): ?Filter
    {
        ray($field, $globalFilterName, $data);

        return null;
    }
}

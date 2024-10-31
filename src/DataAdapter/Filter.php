<?php

namespace LaravelToolkit\DataAdapter;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

readonly class Filter
{
    private function __construct(
        public string $field,
        public MatchMode $matchMode,
        public string $value,
        public bool $global
    ) {}

    /**
     * @return \Illuminate\Support\Collection<string, \LaravelToolkit\DataAdapter\Filter>|null
     */
    public static function create(?array $filters, string $globalFilterName): ?Collection
    {
        if (empty($filters)) {
            return null;
        }

        return collect($filters)
            ->mapWithKeys(function (array $filter, string $key) use ($globalFilterName) {
                if (
                    ($mode = MatchMode::tryFrom(Arr::get($filter, 'matchMode', ''))) !== null &&
                    ! empty(($value = Arr::get($filter, 'value')))
                ) {
                    return [$key => new static($key, $mode, $value, $key === $globalFilterName)];
                }

                return [$key => null];
            })
            ->filter(fn ($filter) => $filter instanceof Filter);
    }
}

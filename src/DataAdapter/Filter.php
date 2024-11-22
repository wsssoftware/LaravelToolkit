<?php

namespace LaravelToolkit\DataAdapter;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @property Collection<int, \LaravelToolkit\DataAdapter\Constraint> $constraints
 */
readonly class Filter
{
    private function __construct(
        public string $field,
        public Operator $operator,
        public Collection $constraints,
        public bool $global,
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
        $operatorValue = trim(Arr::get($data, 'operator', 'and'));
        $operator = Operator::tryFrom(! empty($operatorValue) ? $operatorValue : 'and');
        $constraints = collect(Arr::get($data, 'constraints', [$data]))
            ->map(fn (array $constraint) => Constraint::create($constraint))
            ->filter(fn ($filter) => $filter instanceof Constraint);
        $valid = $constraints->isNotEmpty() && $operator !== null;

        return $valid ? new Filter($field, $operator, $constraints, $field === $globalFilterName) : null;
    }

    public function apply(QueryBuilder $builder): void
    {
        $builder->whereNested(fn (QueryBuilder $query) => $this->constraints->each(
            fn (Constraint $constraint) => $constraint->matchMode->apply(
                $query,
                $this->field,
                $constraint->value,
                $this->operator->value
            )
        ));
    }

    public function applyGlobal(EloquentBuilder $builder, ?array $globalFilterColumns): void
    {
        $constraint = $this->constraints->first();
        $constraint->matchMode->applyGlobal($builder, $globalFilterColumns, $constraint->value);
    }
}

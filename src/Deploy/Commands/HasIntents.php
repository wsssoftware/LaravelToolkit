<?php

namespace LaravelToolkit\Deploy\Commands;

use Illuminate\Support\Collection;
use LaravelToolkit\Deploy\Intent;

trait HasIntents
{
    public function intents(int $step): Collection
    {
        return collect(config("laraveltoolkit.deploy.step$step", []))
            ->map(fn (array $item) => new Intent($item[0], $item[1]));
    }
}

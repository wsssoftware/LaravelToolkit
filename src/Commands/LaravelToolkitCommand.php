<?php

namespace LaravelToolkit\Commands;

use Illuminate\Console\Command;

class LaravelToolkitCommand extends Command
{
    public $signature = 'laraveltoolkit';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

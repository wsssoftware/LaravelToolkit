<?php

namespace LaravelToolkit\Tests\Deploy;

class Command extends \Illuminate\Console\Command
{
    public function call($command, array $arguments = []): int
    {
        return 0;
    }
}

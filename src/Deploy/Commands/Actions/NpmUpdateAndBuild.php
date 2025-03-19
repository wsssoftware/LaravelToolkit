<?php

namespace LaravelToolkit\Deploy\Commands\Actions;

use Symfony\Component\Process\Process;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\spin;

class NpmUpdateAndBuild extends Action
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npm:update {--c|cwd= : Current work directory for command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the npm packages';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (self::$fake) {
            return self::SUCCESS;
        }
        $buildFrontEnd = confirm('Do you want update NPM and Build Frontend??', true);
        if ($buildFrontEnd) {
            $this->npmUpdate();
            $this->build();
        } else {
            $this->components->warn('NPM update and build skipped.');
        }

        return self::SUCCESS;
    }

    protected function npmUpdate(): void
    {
        $process = new Process(['npm', 'update'], $this->getCwdOption(), timeout: 300);
        $result = spin(fn () => $process->run(), 'Updating npm packages...');
        if ($result === 0) {
            $this->components->info('NPM updated successfully!');
        } else {
            $this->components->error($process->getErrorOutput());
        }
    }

    protected function build(): void
    {
        $cwd = $this->getCwdOption();
        $this->components->info(sprintf('Building resources on "%s"', $cwd));

        $process = new Process(['npm', 'run', 'build'], $cwd, timeout: 300);
        $result = spin(fn () => $process->run(), 'Building Frontend using vite...');
        $process->start();
        if ($result === 0) {
            $this->components->info('Frontend built successfully!');
        } else {
            $this->components->error($process->getErrorOutput());
        }
    }

    public static function shortLabel(): string
    {
        return 'NPM Update';
    }
}

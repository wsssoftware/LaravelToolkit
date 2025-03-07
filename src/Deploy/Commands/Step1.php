<?php

namespace LaravelToolkit\Deploy\Commands;

use Illuminate\Support\Facades\Cache;
use LaravelToolkit\Deploy\Events\MaintenanceEnabledEvent;

class Step1 extends Step
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will deploy the application step1';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Deploying application Step 1');

        $this->down();
        MaintenanceEnabledEvent::dispatch();

        //        $this->call('git:pull', ['--release' => '2.']);
        //        Cache::forget(Onnix::VERSION_CACHE_KEY);
        //
        //        $this->call('composer:update');
        //
        //        $this->components->warn('Step 1 finished, php deploy gain and choose step 2');

        Cache::remember('must_offer_step_two_as_default', 300, fn () => true);

        return self::SUCCESS;
    }

    protected function down(): void
    {
        $params = [
            '--secret' => config('laraveltoolkit.deploy.bypass_secret'),
            '--redirect' => config('laraveltoolkit.deploy.path'),
        ];
        $this->call('down', $params);
    }
}

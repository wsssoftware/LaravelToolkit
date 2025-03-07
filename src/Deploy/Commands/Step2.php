<?php

namespace LaravelToolkit\Deploy\Commands;

use Illuminate\Support\Facades\Cache;
use LaravelToolkit\Deploy\Events\MaintenanceDisabledEvent;
use LaravelToolkit\Deploy\Intent;

use function Laravel\Prompts\confirm;

class Step2 extends Step
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will deploy the application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->alert('Deploying application Step 2');
        $this->intents(2)
            ->each(fn (Intent $intent) => $intent->call($this));

        //        $this->call('migrate', ['--force' => true]);
        //        $this->call('db:seed', ['--force' => true]);
        //
        //        $this->call('cache:clear');
        //
        //        if (
        //            $isProduction ||
        //            confirm('This isn\'t a production application, do you want to cache route, config and views?', false)
        //        ) {
        //            $this->call('route:cache');
        //            $this->call('config:cache');
        //            $this->call('event:cache');
        //            $this->call('view:cache');
        //            $this->call('storage:link');
        //        } else {
        //            $this->call('route:clear');
        //            $this->call('config:clear');
        //            $this->call('event:clear');
        //            $this->call('view:clear');
        //        }
        //
        //        $this->call('horizon:terminate');
        //        $this->call('pm2:restart');
        //

        $this->call('up');
        Cache::forget('must_offer_step_two_as_default');
        MaintenanceDisabledEvent::dispatch();

        return self::SUCCESS;
    }
}

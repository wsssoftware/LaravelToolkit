<?php

namespace LaravelToolkit\StoredAssets\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use LaravelToolkit\Facades\StoredAssets;

class GarbageCollectorManager implements ShouldQueue, ShouldBeUnique
{
    use Queueable, HasDisk;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected ?array $disks = null,
    ) {
        $this->disks = $this->disks ?? [StoredAssets::defaultDisk()];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        GarbageCollector::clearCount();
        $chain = [];
        foreach ($this->disks as $diskName) {
            $chain[] = new TrashBinCleaner($diskName);
            $disk = $this->disk($diskName);
            foreach (collect($disk->directories(StoredAssets::basePath()))->shuffle() as $directory) {
                if ($directory === str(StoredAssets::trashBinPath())->trim(DIRECTORY_SEPARATOR)->toString()) {
                    continue;
                }
                $chain[] = new GarbageCollector($diskName, $directory);
            }
        }
        $chain[] = new GarbageCollectorSummary();
        Bus::chain($chain)
            ->onQueue($this->queue)
            ->onConnection($this->connection)
            ->dispatch();
    }
}

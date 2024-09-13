<?php

namespace LaravelToolkit\Console\Commands;

use Illuminate\Console\Command;

class InstallSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraveltoolkit:install_sitemap
                            {--force : Overwrite any existing Sitemap routes file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Laravel Toolkit sitemap routes file.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (file_exists($sitemapRoutesPath = $this->laravel->basePath('routes/sitemap.php')) &&
            ! $this->option('force')) {
            $this->components->error('Sitemap routes file already exists.');
        } else {
            $this->components->info('Published Sitemap routes file.');
            copy(dirname(__DIR__, 3).'/routes/sitemap.php', $sitemapRoutesPath);
        }
    }
}

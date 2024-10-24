<?php

namespace LaravelToolkit\Tests;

use Illuminate\Support\ServiceProvider;
use LaravelToolkit\Facades\ACL;
use LaravelToolkit\Tests\Model\UserPermission;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ACL::withModel(UserPermission::class)
            ->withRolesEnum(UserRole::class);
    }
}

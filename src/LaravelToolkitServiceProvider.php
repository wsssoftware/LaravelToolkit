<?php

namespace LaravelToolkit;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use LaravelToolkit\ACL\MakeACLModelCommand;
use LaravelToolkit\ACL\RolesFirewallMiddleware;
use LaravelToolkit\Facades\ACL;
use LaravelToolkit\Macros\BlueprintMacro;
use LaravelToolkit\Macros\CollectionMacro;
use LaravelToolkit\Macros\RequestMacro;
use LaravelToolkit\Macros\StrMacro;
use LaravelToolkit\Routing\Redirector as PackageRedirector;
use LaravelToolkit\SEO\SEOComponent;
use LaravelToolkit\StoredAssets\MakeStoreRecipeCommand;
use LaravelToolkit\Support\Document\CNPJ;
use LaravelToolkit\Support\Document\CPF;
use LaravelToolkit\Support\Document\Generic as DocumentGeneric;
use LaravelToolkit\Support\Phone\Generic as PhoneGeneric;
use LaravelToolkit\Support\Phone\Landline;
use LaravelToolkit\Support\Phone\LocalFare;
use LaravelToolkit\Support\Phone\Mobile;
use LaravelToolkit\Support\Phone\NonRegional;
use LaravelToolkit\Support\Phone\PublicServices;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelToolkitServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laraveltoolkit')
            ->hasConfigFile('laraveltoolkit')
            ->hasRoute('web')
            ->hasTranslations()
            ->hasViews()
            ->hasCommands([
                MakeACLModelCommand::class,
                MakeStoreRecipeCommand::class,
            ])
            ->hasMigration('create_stored_assets_table')
            ->hasMigration('create_user_permissions_table');

        $this->addPublishGroup('laraveltoolkit-sitemap', [
            dirname(__DIR__).'/routes/sitemap.php' => base_path('routes/sitemap.php'),
        ]);
    }

    public function boot(): self
    {
        $this->bootMacros();
        $this->setLocale();;
        $this->bootContainers();
        $this->bootGates();

        Blade::component('seo', SEOComponent::class);

        return parent::boot();

    }

    protected function setLocale(): void
    {
        setlocale(
            LC_ALL,
            config('app.locale').'.UTF-8',
            config('app.locale'),
            config('app.fallback_locale').'.UTF-8',
            config('app.fallback_locale'),
            'en.UTF-8',
            'en',
            'en_US.UTF-8',
            'en_US',
        );
    }

    protected function bootMacros(): void
    {
        (new BlueprintMacro)();
        (new CollectionMacro)();
        (new RequestMacro)();
        (new StrMacro)();
    }

    protected function bootContainers(): void
    {
        if (config('laraveltoolkit.extended_redirector')) {
            $this->app->extend('redirect', fn() => app(PackageRedirector::class));
        }

        // Documents Singleton
        $this->app->singleton(CNPJ::class, fn() => new CNPJ);
        $this->app->singleton(CPF::class, fn() => new CPF);
        $this->app->singleton(DocumentGeneric::class, fn() => new DocumentGeneric);

        // Phones Singleton
        $this->app->singleton(Landline::class, fn() => new Landline);
        $this->app->singleton(LocalFare::class, fn() => new LocalFare);
        $this->app->singleton(Mobile::class, fn() => new Mobile);
        $this->app->singleton(NonRegional::class, fn() => new NonRegional);
        $this->app->singleton(PublicServices::class, fn() => new PublicServices);
        $this->app->singleton(PhoneGeneric::class, fn() => new PhoneGeneric);
    }

    protected function bootGates(): void
    {
        $this->app->booted(callback: function () {
            /** @var \LaravelToolkit\ACL\UserPermission|null $model */
            $model = ACL::model();
            if ($model === null) {
                return;
            }
            /** @var \App\Models\UserPermission $userPermission */
            $userPermission = new ($model);
            foreach ($userPermission->getPolicies() as $policy) {
                foreach ($policy->rules as $rule) {
                    Gate::define(
                        "$policy->column::$rule->key",
                        fn(User $user) => ACL::userPermission($user)->{$policy->column}->{$rule->key}->value
                            ? Response::allow()
                            : (is_int($rule->denyStatus) ? Response::denyWithStatus($rule->denyStatus) : Response::deny())
                    );
                }
            }

        });
        $this->app->booted(callback: function () {
            /** @var \BackedEnum&\LaravelToolkit\ACL\HasDenyResponse|null $rolesEnum */
            $rolesEnum = ACL::rolesEnum();
            if ($rolesEnum === null) {
                return;
            }
            Route::aliasMiddleware('user_roles', RolesFirewallMiddleware::class);
            foreach ($rolesEnum::cases() as $enum) {
                Gate::define(
                    "roles::$enum->value",
                    fn(User $user) => ACL::userPermission($user)->roles->filter(fn($r) => $r === $enum)->isNotEmpty()
                        ? Response::allow()
                        : $enum->denyResponse()
                );
            }
        });
    }
}

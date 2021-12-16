<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        ServerProvider::class => DigitalOceanServerProvider::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Command\CreateApiKey::class,
                Console\Command\DeleteApiKey::class,
                Console\Command\CreateScope::class,
                Console\Command\DeleteScope::class,
                Console\Command\AddScopeToApiKey::class,
                Console\Command\RemoveScopeFromApiKey::class,
                Console\Command\PrintApiKey::class,
            ]);
        }
    }
}

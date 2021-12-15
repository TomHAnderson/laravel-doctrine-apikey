<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
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
                Command\CreateApiKey::class,
                Command\DeleteApiKey::class,
                Command\CreateScope::class,
                Command\DeleteScope::class,
                Command\AddScopeToApiKey::class,
                Command\RemoveScopeFromApiKey::class,
            ]);
        }
    }
}

<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Http\Middleware\AuthorizeApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
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
        $this->app->singleton(ApiKeyService::class, function ($app) {
            return new ApiKeyService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['router']->middleware('auth.apiKey', AuthorizeApiKey::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Command\GenerateApiKey::class,
                Console\Command\DeactivateApiKey::class,
                Console\Command\ActivateApiKey::class,
                Console\Command\GenerateScope::class,
                Console\Command\DeleteScope::class,
                Console\Command\AddScope::class,
                Console\Command\RemoveScope::class,
                Console\Command\PrintApiKey::class,
            ]);
        }
    }
}

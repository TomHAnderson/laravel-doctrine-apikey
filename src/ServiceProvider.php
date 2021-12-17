<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Http\Middleware\AuthorizeApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiKeyService::class, static function ($app) {
            return new ApiKeyService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['router']->middleware('auth.apiKey', AuthorizeApiKey::class);

        if (! $this->app->runningInConsole()) {
            return;
        }

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

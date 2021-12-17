<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey;

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
    // phpcs:disable SlevomatCodingStandard.ControlStructures.EarlyExit.EarlyExitNotUsed
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Command\ActivateApiKey::class,
                Console\Command\AddScope::class,
                Console\Command\DeactivateApiKey::class,
                Console\Command\DeleteScope::class,
                Console\Command\GenerateApiKey::class,
                Console\Command\GenerateScope::class,
                Console\Command\PrintApiKey::class,
                Console\Command\PrintScope::class,
                Console\Command\RemoveScope::class,
            ]);
        }
    }
}

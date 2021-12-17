<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class PrintScope extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:scope:print {name?}';

    /**
     * The console command description.
     */
    protected $description = 'Print scopes';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $name = $this->argument('name');

        $scopeRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(Scope::class);

        if ($name) {
            $scopes = $scopeRepository->findBy(['name' => $name]);

            if (! $scopes) {
                $this->error('Invalid scope name');

                return 1;
            }
        } else {
            $scopes = $scopeRepository->findBy([], ['name' => 'asc']);
        }

        $this->printScopes($scopes);

        return 0;
    }
}

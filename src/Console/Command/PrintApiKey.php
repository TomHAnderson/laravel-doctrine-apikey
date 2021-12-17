<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class PrintApiKey extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:print {name?}';

    /**
     * The console command description.
     */
    protected $description = 'Print apikeys';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $name = $this->argument('name');

        $apiKeyRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(ApiKey::class);

        if ($name) {
            $apiKeys = $apiKeyRepository->findBy(['name' => $name]);

            if (! $apiKeys) {
                $this->error('Invalid apikey name');

                return 1;
            }
        } else {
            $apiKeys = $apiKeyRepository->findBy([], ['name' => 'asc']);
        }

        $this->printApiKeys($apiKeys);

        return 0;
    }
}

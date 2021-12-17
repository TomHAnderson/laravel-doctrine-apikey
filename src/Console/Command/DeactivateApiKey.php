<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class DeactivateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:deactivate {name}';

    /**
     * The console command description.
     */
    protected $description = 'Deactivate an ApiKey';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $name = $this->argument('name');

        $apiKeyRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(ApiKey::class);

        $apiKey = $apiKeyRepository->findOneBy(['name' => $name]);

        if (! $apiKey) {
            $this->error('Invalid apikey name');

            return 1;
        }

        $apiKeyRepository->updateActive($apiKey, false);
        $this->apiKeyService->getEntityManager()->flush();

        $this->printApiKeys([$apiKey]);

        return 0;
    }
}

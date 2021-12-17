<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class ActivateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:activate {name}';

    /**
     * The console command description.
     */
    protected $description = 'Activate an ApiKey';

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

        $apiKeyRepository->updateActive($apiKey, true);
        $this->apiKeyService->getEntityManager()->flush();

        $this->printApiKeys([$apiKey]);

        return 0;
    }
}

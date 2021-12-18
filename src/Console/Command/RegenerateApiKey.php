<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use Throwable;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class RegenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:regenerate {name}';

    /**
     * The console command description.
     */
    protected $description = 'Regenerate an apikey';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $name = $this->argument('name');

        $apiKeyRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(ApiKey::class);

        try {
            $apiKey = $apiKeyRepository->findOneBy(['name' => $name]);
        } catch (Throwable $e) {
            $this->error('ApiKey not found by name: ' . $name);

            return 1;
        }

        $apiKey = $apiKeyRepository->regenerate($apiKey);

        $this->apiKeyService->getEntityManager()->flush();
        $this->printApiKeys([$apiKey]);

        return 0;
    }
}

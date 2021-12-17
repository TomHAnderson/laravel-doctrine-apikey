<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ApiKeyDoesNotHaveScope;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class RemoveScope extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:scope:remove {apiKeyName} {scopeName}';

    /**
     * The console command description.
     */
    protected $description = 'Remove a scope from an apikey';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $apiKeyName = $this->argument('apiKeyName');
        $scopeName  = $this->argument('scopeName');

        $apiKeyRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(ApiKey::class);
        $scopeRepository  = $this->apiKeyService->getEntityManager()
            ->getRepository(Scope::class);

        $apiKey = $apiKeyRepository->findOneBy(['name' => $apiKeyName]);
        if (! $apiKey) {
            $this->error('Cannot find apikey with name: ' . $apiKeyName);

            return 1;
        }

        $scope = $scopeRepository->findOneBy(['name' => $scopeName]);
        if (! $scope) {
            $this->error('Cannot find scope with name: ' . $scopeName);

            return 1;
        }

        try {
            $apiKeyRepository->removeScope($apiKey, $scope);
            $this->apiKeyService->getEntityManager()->flush();
        } catch (ApiKeyDoesNotHaveScope $e) {
            $this->error($e->getMessage());

            return 1;
        }

        $this->printApiKeys([$apiKey]);

        return 0;
    }
}

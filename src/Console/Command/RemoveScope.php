<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ApiKeyDoesNotHaveScope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

use function implode;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class RemoveScope extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:scope:remove {apiKeyName} {scopeName}';

    /**
     * The console command description.
     */
    protected $description = 'Remove a Scope from an ApiKey';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ApiKeyService $apiKeyService)
    {
        parent::__construct();

        $this->apiKeyService = $apiKeyService;
    }

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
            $this->error('Cannot find ApiKey with name: ' . $apiKeyName);

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

        $scopeNames = [];
        foreach ($apiKey->getScopes() as $s) {
            $scopeNames[] = $s->getName();
        }

        $headers = ['name', 'key', 'status', 'scopes'];
        $rows    = [
            [
                $apiKey->getName(),
                $apiKey->getApiKey(),
                $apiKey->getIsActive() ? 'active' : 'deactivated',
                implode(',', $scopeNames),
            ],
        ];

        $this->table($headers, $rows);

        return 0;
    }
}

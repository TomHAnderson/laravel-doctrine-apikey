<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateScopeForApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ScopeHasApiKeys;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

final class AddScope extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:scope:add {apiKeyName} {scopeName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a Scope to an ApiKey';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $apiKeyName = $this->argument('apiKeyName');
        $scopeName = $this->argument('scopeName');

        $apiKeyRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(ApiKey::class);
        $scopeRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(Scope::class);

        $apiKey = $apiKeyRepository->findOneBy([
            'name' => $apiKeyName,
        ]);
        if (! $apiKey) {
            $this->error('Cannot find ApiKey with name: ' . $apiKeyName);

            return 1;
        }

        $scope = $scopeRepository->findOneBy([
            'name' => $scopeName,
        ]);
        if (! $scope) {
            $this->error('Cannot find scope with name: ' . $scopeName);

            return 1;
        }

        try {
            $apiKeyRepository->addScope($apiKey, $scope);
            $this->apiKeyService->getEntityManager()->flush();
        } catch (DuplicateScopeForApiKey $e) {
            $this->error($e->getMessage());

            return 1;
        }

        $scopeNames = [];
        foreach ($apiKey->getScopes() as $s) {
            $scopeNames[] = $s->getName();
        }

        $headers = ['name', 'key', 'status', 'scopes'];
        $rows = [[
            $apiKey->getName(),
            $apiKey->getKey(),
            $apiKey->getIsActive() ? 'active': 'deactivated',
            implode(',', $scopeNames)
        ]];

        $this->table($headers, $rows);

        return 0;
    }
}

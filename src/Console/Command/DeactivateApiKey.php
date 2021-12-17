<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

final class DeactivateApiKey extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:deactivate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate an ApiKey';

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
        $name = $this->argument('name');

        $apiKeyRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(ApiKey::class);

        $apiKey = $apiKeyRepository->findOneBy([
            'name' => $name,
        ]);

        if (! $apiKey) {
            $this->error('Invalid apiKey name');

            return 1;
        }

        $apiKeyRepository->updateActive($apiKey, false);
        $this->apiKeyService->getEntityManager()->flush();

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

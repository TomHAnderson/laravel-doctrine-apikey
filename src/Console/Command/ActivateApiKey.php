<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

use function implode;

final class ActivateApiKey extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     */
    protected string $signature = 'apikey:activate {name}';

    /**
     * The console command description.
     */
    protected string $description = 'Activate an ApiKey';

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
        $name = $this->argument('name');

        $apiKeyRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(ApiKey::class);

        $apiKey = $apiKeyRepository->findOneBy(['name' => $name]);

        if (! $apiKey) {
            $this->error('Invalid apiKey name');

            return 1;
        }

        $apiKeyRepository->updateActive($apiKey, true);
        $this->apiKeyService->getEntityManager()->flush();

        $scopeNames = [];
        foreach ($apiKey->getScopes() as $s) {
            $scopeNames[] = $s->getName();
        }

        $headers = ['name', 'key', 'status', 'scopes'];
        $rows    = [
            [
                $apiKey->getName(),
                $apiKey->getKey(),
                $apiKey->getIsActive() ? 'active' : 'deactivated',
                implode(',', $scopeNames),
            ],
        ];

        $this->table($headers, $rows);

        return 0;
    }
}

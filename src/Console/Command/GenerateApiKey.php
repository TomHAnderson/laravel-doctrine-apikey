<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

use function implode;

final class GenerateApiKey extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     */
    protected string $signature = 'apikey:generate {name}';

    /**
     * The console command description.
     */
    protected string $description = 'Create a new ApiKey';

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

        try {
            $apiKey = $apiKeyRepository->generate($name);
        } catch (InvalidName $e) {
            $this->error($e->getMessage());

            return 1;
        } catch (DuplicateName $e) {
            $this->error($e->getMessage());

            return 1;
        }

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

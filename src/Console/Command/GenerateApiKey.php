<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

final class GenerateApiKey extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new ApiKey';

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

        $headers = ['name', 'key', 'status'];
        $rows = [[$apiKey->getName(), $apiKey->getKey(), $apiKey->getIsActive() ? 'active': 'deactivated']];

        $this->table($headers, $rows);

        return 0;
    }
}

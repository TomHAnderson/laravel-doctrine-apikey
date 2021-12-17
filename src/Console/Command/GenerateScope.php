<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

use function count;

final class GenerateScope extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     */
    protected string $signature = 'apikey:scope:generate {name}';

    /**
     * The console command description.
     */
    protected string $description = 'Generate a new ApiKey Scope';

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

        $scopeRepository = $this->apiKeyService->getEntityManager()
            ->getRepository(Scope::class);

        try {
            $scope = $scopeRepository->generate($name);
        } catch (DuplicateName $e) {
            $this->error($e->getMessage());

            return 1;
        } catch (InvalidName $e) {
            $this->error($e->getMessage());

            return 1;
        }

        $headers = ['name', 'apiKey count'];
        $rows    = [[$scope->getName(), count($scope->getApiKeys())]];

        $this->table($headers, $rows);

        return 0;
    }
}

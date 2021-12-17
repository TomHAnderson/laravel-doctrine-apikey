<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ScopeHasApiKeys;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

final class DeleteScope extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     */
    protected string $signature = 'apikey:scope:delete {name}';

    /**
     * The console command description.
     */
    protected string $description = 'Delete an ApiKey Scope (Delete a scope, not a relationship)';

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

        $scope = $scopeRepository->findOneBy(['name' => $name]);

        if (! $scope) {
            $this->error('Cannot find scope with name: ' . $name);

            return 1;
        }

        try {
            $scopeRepository->delete($scope);
            $this->apiKeyService->getEntityManager()->flush();
        } catch (ScopeHasApiKeys $e) {
            $this->error($e->getMessage());

            return 1;
        }

        $this->info('Scope has been deleted');

        return 0;
    }
}

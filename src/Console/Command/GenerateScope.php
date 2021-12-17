<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Console\Command;

final class GenerateScope extends Command
{
    private ApiKeyService $apiKeyService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:scope:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new ApiKey Scope';

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
        $rows = [[$scope->getName(), sizeof($scope->getApiKeys())]];

        $this->table($headers, $rows);

        return 0;
    }
}

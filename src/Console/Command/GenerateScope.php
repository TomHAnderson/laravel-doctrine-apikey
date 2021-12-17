<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;

use function count;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class GenerateScope extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:scope:generate {name}';

    /**
     * The console command description.
     */
    protected $description = 'Generate a new apikey scope';

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
            $this->apiKeyService->getEntityManager()->flush();
        } catch (DuplicateName $e) {
            $this->error($e->getMessage());

            return 1;
        } catch (InvalidName $e) {
            $this->error($e->getMessage());

            return 1;
        }

        $this->printScopes([$scope]);

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
final class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apikey:generate {name}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new apikey';

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

        $this->printApiKeys([$apiKey]);

        return 0;
    }
}

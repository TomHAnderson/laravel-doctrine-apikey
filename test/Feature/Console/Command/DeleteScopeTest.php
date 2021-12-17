<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ScopeHasApiKeys;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class DeleteScopeTest extends TestCase
{
    public function testDeleteScope(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $scopeRepository = $entityManager->getRepository(Scope::class);
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:delete', [
            'name' => $scope->getName(),
        ])->assertExitCode(0);
    }

    public function testErrorOnInvalidName(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $this->artisan('apikey:scope:delete', [
            'name' => 'invalid',
        ])->assertExitCode(1);
    }

    public function testCannotDeleteScopeWithApiKeys(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->artisan('apikey:scope:delete', [
            'name' => $scope->getName(),
        ])->assertExitCode(1);
    }
}

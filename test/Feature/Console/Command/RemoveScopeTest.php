<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class RemoveScopeTest extends TestCase
{
    public function testRemoveScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->artisan('apikey:scope:remove', [
            'apiKeyName' => $apiKey->getName(),
            'scopeName' => $scope->getName(),
        ])->assertExitCode(0);

        $found = false;
        foreach ($apiKey->getScopes() as $s) {
            if ($scope === $s) {
                $found = true;
            }
        }

        $this->assertFalse($found);
    }

    public function testErrorOnInvalidApiKeyName(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:remove', [
            'apiKeyName' => 'invalid',
            'scopeName' => $scope->getName(),
        ])->assertExitCode(1);
    }

    public function testErrorOnInvalidScopeName(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:remove', [
            'apiKeyName' => $apiKey->getName(),
            'scopeName' => 'invalid',
        ])->assertExitCode(1);
    }

    public function testCannotRemoveScopeFromApiKeyWhichIsNotAssigned(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $scope2 = $scopeRepository->generate('testing2');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->artisan('apikey:scope:remove', [
            'apiKeyName' => $apiKey->getName(),
            'scopeName' => $scope2->getName(),
        ])->assertExitCode(1);
    }
}

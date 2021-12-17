<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class AddScopeTest extends TestCase
{
    public function testAddScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:add', [
            'apiKeyName' => $apiKey->getName(),
            'scopeName' => $scope->getName(),
        ])->assertExitCode(0);
    }

    public function testErrorOnInvalidApiKeyName(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:add', [
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

        $this->artisan('apikey:scope:add', [
            'apiKeyName' => $apiKey->getName(),
            'scopeName' => 'invalid',
        ])->assertExitCode(1);
    }

    public function testCannotAddSameScopeToApiKey(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);

        $this->artisan('apikey:scope:add', [
            'apiKeyName' => $apiKey->getName(),
            'scopeName' => $scope->getName(),
        ])->assertExitCode(1);
    }
}

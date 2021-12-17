<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Service;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

final class ApiKeyServiceTest extends TestCase
{
    public function testInitAndCreateDatabase(): void
    {
        $entityManager = $this->createDatabase(app('em'));

        $this->assertInstanceOf(EntityManager::class, $entityManager);
    }

    public function testInitTwiceBailsOut(): void
    {
        $entityManager = app('em');
        $apiKeyService = app(ApiKeyService::class);

        $apiKeyService->init($entityManager);

        $this->assertFalse($apiKeyService->init($entityManager));
    }

    public function testIsActive(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyService = app(ApiKeyService::class);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);

        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $apiKey = $apiKeyService->isActive($apiKey->getKey());
        $this->assertInstanceOf(ApiKey::class, $apiKey);
    }

    public function testIsActiveReturnsFalseForInvalidKey(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyService = app(ApiKeyService::class);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);

        $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $this->assertFalse($apiKeyService->isActive('invalid'));
    }

    public function testIsActiveReturnsFalseForInactiveKey(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyService = app(ApiKeyService::class);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);

        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $apiKey = $apiKeyRepository->updateActive($apiKey, false);
        $entityManager->flush();

        $this->assertFalse($apiKeyService->isActive($apiKey->getKey()));
    }

    public function testHasScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyService = app(ApiKeyService::class);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $scopeRepository = $entityManager->getRepository(Scope::class);
        $scope = $scopeRepository->generate('scope1');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->assertTrue($apiKeyService->hasScope($apiKey->getKey(), 'scope1'));
    }

    public function testHasScopeReturnsFalseForInvalidApiKey(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyService = app(ApiKeyService::class);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $scopeRepository = $entityManager->getRepository(Scope::class);
        $scope = $scopeRepository->generate('scope1');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->assertFalse($apiKeyService->hasScope('invalid', 'scope1'));
    }

    public function testHasScopeReturnsFalseForInvalidScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyService = app(ApiKeyService::class);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $scopeRepository = $entityManager->getRepository(Scope::class);
        $scope = $scopeRepository->generate('scope1');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->assertFalse($apiKeyService->hasScope($apiKey->getKey(), 'scope2'));
    }
}

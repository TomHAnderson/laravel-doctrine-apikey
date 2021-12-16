<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;

final class ApiKeyRepositoryTest extends TestCase
{
    public function testCreate(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);

        $apiKey = $repository->create('testing');

        $entityManager->flush();
        $this->assertGreaterThan(0, $apiKey->getId());
        $this->assertEquals('testing', $apiKey->getName());
        $this->assertEquals(64, strlen($apiKey->getKey()));
        $this->assertEquals(false, $apiKey->getIsDeleted());
    }

    public function testDelete(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $repository->create('testing');
        $entityManager->flush();

        $repository->delete($apiKey);
        $entityManager->flush();

        $this->assertNotNull($apiKey->getDeletedAt());
        $this->assertEquals(true, $apiKey->getIsDeleted());
    }

    public function addScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $repository->create('testing');
        $entityManager->flush();

        $scope = $scopeRepository->create('test1');

        $repository->addScope($apiKey, $scope);
    }



}

<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ScopeHasApiKeys;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class ScopeRepositoryTest extends TestCase
{
    public function testGenerate(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(Scope::class);

        $now = new DateTime();

        $scope = $repository->generate('testing');
        $entityManager->flush();

        $this->assertGreaterThan(0, $scope->getId());
        $this->assertGreaterThan($now, $scope->getCreatedAt());
        $this->assertEquals('testing', $scope->getName());
    }

    public function testDelete(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(Scope::class);
        $scope = $repository->generate('testing');
        $entityManager->flush();

        $repository->delete($scope);
        $entityManager->flush();

        $this->assertEmpty($repository->findOneBy(['name' => 'testing']));
    }

    public function testGenerateValidatesName(): void
    {
        $this->expectException(InvalidName::class);

        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(Scope::class);

        $repository->generate('test^name');
    }

    public function testCannotCreateDuplicateScope(): void
    {
        $this->expectException(DuplicateName::class);

        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(Scope::class);
        $repository->generate('testing');
        $entityManager->flush();

        $repository->generate('testing');
    }

    public function testCannotDeleteScopeWithApiKeys(): void
    {
        $this->expectException(ScopeHasApiKeys::class);

        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $result = $scopeRepository->delete($scope);
    }
}

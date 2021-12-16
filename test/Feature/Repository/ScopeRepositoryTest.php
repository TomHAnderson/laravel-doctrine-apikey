<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;

final class ScopeRepositoryTest extends TestCase
{
    public function testCreate(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(Scope::class);

        $scope = $repository->create('testing');
        $entityManager->flush();

        $this->assertGreaterThan(0, $scope->getId());
        $this->assertEquals('testing', $scope->getName());
    }

    public function testDelete(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(Scope::class);
        $scope = $repository->create('testing');
        $entityManager->flush();

        $repository->delete($scope);
        $entityManager->flush();

        $this->assertEmpty($repository->findOneBy(['name' => 'testing']));
    }
}

<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AdminEvent;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ApiKeyDoesNotHaveScope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateScopeForApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class ApiKeyRepositoryTest extends TestCase
{
    public function testGenerate(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);

        $apiKey = $repository->generate('testing');
        $entityManager->flush();

        $this->assertGreaterThan(0, $apiKey->getId());
        $this->assertEquals('testing', $apiKey->getName());
        $this->assertEquals(64, strlen($apiKey->getApiKey()));
        $this->assertEquals(true, $apiKey->getIsActive());

        $adminEvents = $entityManager->getRepository(AdminEvent::class)
            ->findAll();

        foreach ($adminEvents as $adminEvent) {
            $this->assertEquals('generate', $adminEvent->getEvent());
            $this->assertEquals($apiKey, $adminEvent->getApiKey());
        }
    }

    public function testGenerateValidatesName(): void
    {
        $this->expectException(InvalidName::class);

        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);

        $apiKey = $repository->generate('test^name');
    }

    public function testGenerateDoesNotCollideNames(): void
    {
        $this->expectException(DuplicateName::class);

        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);

        $apiKey = $repository->generate('testing');
        $entityManager->flush();

        $result = $repository->generate('testing');
    }

    public function testDeativate(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $repository->generate('testing');
        $entityManager->flush();

        $beforeSetStatus = new DateTime();

        $this->assertEquals(true, $apiKey->getIsActive());

        $repository->updateActive($apiKey, false);
        $entityManager->flush();

        $this->assertGreaterThan($beforeSetStatus, $apiKey->getStatusAt());
        $this->assertEquals(false, $apiKey->getIsActive());

        $adminEvents = $entityManager->getRepository(AdminEvent::class)
            ->findBy([
                'apiKey' => $apiKey,
                'event' => 'deactivate',
            ]);

        $this->assertEquals(1,  count($adminEvents));
        foreach ($adminEvents as $adminEvent) {
            $this->assertEquals('deactivate', $adminEvent->getEvent());
            $this->assertEquals($apiKey, $adminEvent->getApiKey());
        }
    }

    public function testActivate(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $repository->generate('testing');
        $apiKey->setIsActive(false);
        $entityManager->flush();

        $beforeSetStatus = new DateTime();

        $this->assertEquals(false, $apiKey->getIsActive());

        $repository->updateActive($apiKey, true);
        $entityManager->flush();

        $this->assertGreaterThan($beforeSetStatus, $apiKey->getStatusAt());
        $this->assertEquals(true, $apiKey->getIsActive());

        $adminEvents = $entityManager->getRepository(AdminEvent::class)
            ->findBy([
                'apiKey' => $apiKey,
                'event' => 'activate',
            ]);

        $this->assertEquals(1,  count($adminEvents));
        foreach ($adminEvents as $adminEvent) {
            $this->assertEquals('activate', $adminEvent->getEvent());
            $this->assertEquals($apiKey, $adminEvent->getApiKey());
        }
    }

    public function testAddScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $repository->generate('testing');
        $entityManager->flush();

        $scope = $scopeRepository->generate('test1');
        $entityManager->flush();

        $repository->addScope($apiKey, $scope);
        $entityManager->flush();

        $found = false;
        foreach ($apiKey->getScopes() as $s) {
            if ($scope === $s) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);

        $adminEvents = $entityManager->getRepository(AdminEvent::class)
            ->findBy([
                'apiKey' => $apiKey,
                'event' => 'add scope: ' . $scope->getName(),
            ]);

        $this->assertEquals(1,  count($adminEvents));
        foreach ($adminEvents as $adminEvent) {
            $this->assertEquals('add scope: ' . $scope->getName(), $adminEvent->getEvent());
            $this->assertEquals($apiKey, $adminEvent->getApiKey());
        }
    }

    public function testCannotAddSameScopeTwice(): void
    {
        $this->expectException(DuplicateScopeForApiKey::class);

        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $scope = $scopeRepository->generate('test1');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
    }

    public function testRemoveScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $scope = $scopeRepository->generate('test1');
        $entityManager->flush();
        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->assertEquals(1, sizeof($apiKey->getScopes()));

        $apiKeyRepository->removeScope($apiKey, $scope);
        $entityManager->flush();

        $this->assertEquals(0, sizeof($apiKey->getScopes()));

        $adminEvents = $entityManager->getRepository(AdminEvent::class)
            ->findBy([
                'apiKey' => $apiKey,
                'event' => 'remove scope: ' . $scope->getName(),
            ]);

        $this->assertEquals(1,  count($adminEvents));
        foreach ($adminEvents as $adminEvent) {
            $this->assertEquals('remove scope: ' . $scope->getName(), $adminEvent->getEvent());
            $this->assertEquals($apiKey, $adminEvent->getApiKey());
        }
    }

    public function testRemoveScopeWhichIsNotAssigned(): void
    {
        $this->expectException(ApiKeyDoesNotHaveScope::class);

        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $scope = $scopeRepository->generate('testing');
        $scope2 = $scopeRepository->generate('testing2');
        $entityManager->flush();
        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->assertEquals(1, sizeof($apiKey->getScopes()));

        $apiKeyRepository->removeScope($apiKey, $scope2);
    }
}

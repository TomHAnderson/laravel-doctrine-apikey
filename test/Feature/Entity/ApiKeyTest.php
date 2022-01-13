<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Entity;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Http\Middleware\AuthorizeApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;
use Illuminate\Http\Request;

final class ApiKeyTest extends TestCase
{
    public function testAdminEvent(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);

        $now = new DateTime();

        $apiKey = $repository->generate('testing');
        $entityManager->flush();

        $this->assertGreaterThan(0, $apiKey->getId());
        $this->assertEquals('testing', $apiKey->getName());
        $this->assertEquals(64, strlen($apiKey->getApiKey()));
        $this->assertEquals(true, $apiKey->getIsActive());
        $this->assertGreaterThan($now, $apiKey->getCreatedAt());

        foreach ($apiKey->getAdminEvents() as $adminEvent) {
            $this->assertEquals('generate', $adminEvent->getEvent());
            $this->assertEquals($apiKey, $adminEvent->getApiKey());

            $adminEvent->getApiKey()->removeAdminEvent($adminEvent);
        }

        $this->assertEquals(0, count($apiKey->getAdminEvents()));
    }

    public function testAccessEvent(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);

        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $request = Request::create('/apiresource', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $apiKey->getApiKey());

        $middleware = new AuthorizeApiKey(app(ApiKeyService::class));

        $response = $middleware->handle($request, function() {});
        $this->assertNull($response);

        foreach ($apiKey->getAccessEvents() as $accessEvent) {

            $apiKey->removeAccessEvent($accessEvent);
        }

        $this->assertEquals(0, count($apiKey->getAccessEvents()));
    }

    public function testHasScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);

        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('scopetest');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $this->assertTrue($apiKey->hasScope('scopetest'));
        $this->assertFalse($apiKey->hasScope('fail'));
    }
}

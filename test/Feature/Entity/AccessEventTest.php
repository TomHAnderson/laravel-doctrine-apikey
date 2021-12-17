<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Entity;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Http\Middleware\AuthorizeApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;
use Illuminate\Http\Request;

final class AccessEventTest extends TestCase
{
    public function testAccessEvent(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);

        $now = new DateTime();

        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $request = Request::create('/apiresource', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $apiKey->getApiKey());

        $middleware = new AuthorizeApiKey(app(ApiKeyService::class));

        $response = $middleware->handle($request, function() {});
        $this->assertNull($response);

        foreach ($apiKey->getAccessEvents() as $accessEvent) {
            $this->assertGreaterThan(0, $accessEvent->getId());
            $this->assertGreaterThan($now, $accessEvent->getCreatedAt());
            $this->assertEquals(request()->ip(), $accessEvent->getIpAddress());
            $this->assertEquals('http://localhost/apiresource', $accessEvent->getUrl());

            $accessEvent->getApiKey()->removeAccessEvent($accessEvent);
        }

        $this->assertEquals(0, count($apiKey->getAccessEvents()));
    }
}

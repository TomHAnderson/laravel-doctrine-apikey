<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Http\Middleware;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Http\Middleware\AuthorizeApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use Illuminate\Http\Request;

final class AuthorizeApiKeyTest extends TestCase
{
    public function testApiKeyAuthorizesRequest(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);

        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $request = Request::create('/apiresource', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $apiKey->getKey());

        $middleware = new AuthorizeApiKey(app(ApiKeyService::class));

        $response = $middleware->handle($request, function() {});
        $this->assertNull($response);
    }

    public function testApiKeyDoesNotAuthorizeRequest(): void
    {
        $entityManager = $this->createDatabase(app('em'));

        $request = Request::create('/apiresource', 'GET');

        $middleware = new AuthorizeApiKey(app(ApiKeyService::class));

        $response = $middleware->handle($request, function() {});
        $this->assertNotNull($response);
    }

    public function testApiKeyAuthorizesRequestWithScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);

        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('scopetest');
        $entityManager->flush();

        $apiKeyRepository->addScope($apiKey, $scope);
        $entityManager->flush();

        $request = Request::create('/apiresource', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $apiKey->getKey());

        $middleware = new AuthorizeApiKey(app(ApiKeyService::class));

        $response = $middleware->handle($request, function() {}, $scope->getName());
        $this->assertNull($response);
    }

    public function testApiKeyDoesNotAuthorizeRequestWithInvalidScope(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);

        $apiKey = $apiKeyRepository->generate('testing');
        $scope = $scopeRepository->generate('scopetest');
        $entityManager->flush();

        $request = Request::create('/apiresource', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $apiKey->getKey());

        $middleware = new AuthorizeApiKey(app(ApiKeyService::class));

        $response = $middleware->handle($request, function() {}, $scope->getName());
        $this->assertNotNull($response);
    }
}

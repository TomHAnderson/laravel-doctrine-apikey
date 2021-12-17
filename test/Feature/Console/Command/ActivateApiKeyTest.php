<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class ActivateApiKeyTest extends TestCase
{
    public function testActivateApiKey(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $apiKey->setIsActive(false);
        $entityManager->flush();

        $this->assertFalse($apiKey->getIsActive());

        $this->artisan('apikey:activate', [
            'name' => $apiKey->getName(),
        ])->assertExitCode(0);

        $this->assertTrue($apiKey->getIsActive());
    }

    public function testErrorOnInvalidName(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:activate', [
            'name' => 'invalid',
        ])->assertExitCode(1);
    }
}

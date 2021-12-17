<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class DeactivateApiKeyTest extends TestCase
{
    public function testDeactivateApiKey(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:deactivate', [
            'name' => $apiKey->getName(),
        ])->assertExitCode(0);

        $this->assertFalse($apiKey->getIsActive());
    }

    public function testErrorOnInvalidName(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:deactivate', [
            'name' => 'invalid',
        ])->assertExitCode(1);
    }
}

<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class PrintApiKeyTest extends TestCase
{
    public function testPrintApiKey(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:print', [
            'name' => $apiKey->getName(),
        ])->assertExitCode(0);
    }

    public function testErrorOnInvalidName(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:print', [
            'name' => 'invalid',
        ])->assertExitCode(1);
    }
}

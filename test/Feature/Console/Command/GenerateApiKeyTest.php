<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class GenerateApiKeyTest extends TestCase
{
    public function testGenerateApiKey(): void
    {
        $this->createDatabase(app('em'));

        $this->artisan('apikey:generate', [
            'name' => 'testing',
        ])->assertExitCode(0);
    }

    public function testInvalidNameThrowsError(): void
    {
        $this->createDatabase(app('em'));

        $this->artisan('apikey:generate', [
            'name' => 'test^ing',
        ])->assertExitCode(1);
    }

    public function testDuplicateNameThrowsError(): void
    {
        $entityManager = app('em');

        $this->createDatabase($entityManager);

        $apiKeyRepository = $entityManager->getRepository(ApiKey::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:generate', [
            'name' => $apiKey->getName(),
        ])->assertExitCode(1);
    }
}

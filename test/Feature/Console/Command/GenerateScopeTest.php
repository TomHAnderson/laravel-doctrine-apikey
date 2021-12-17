<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;

final class GenerateScopeTest extends TestCase
{
    public function testGenerateScope(): void
    {
        $this->createDatabase(app('em'));

        $this->artisan('apikey:scope:generate', [
            'name' => 'testing',
        ])->assertExitCode(0);
    }

    public function testInvalidNameThrowsError(): void
    {
        $this->createDatabase(app('em'));

        $this->artisan('apikey:scope:generate', [
            'name' => 'test^ing',
        ])->assertExitCode(1);
    }

    public function testDuplicateNameThrowsError(): void
    {
        $entityManager = app('em');

        $this->createDatabase($entityManager);

        $apiKeyRepository = $entityManager->getRepository(Scope::class);
        $apiKey = $apiKeyRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:generate', [
            'name' => $apiKey->getName(),
        ])->assertExitCode(1);
    }
}

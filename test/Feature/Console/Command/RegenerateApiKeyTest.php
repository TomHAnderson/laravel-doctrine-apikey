<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class ReenerateApiKeyTest extends TestCase
{
    public function testReenerateApiKey(): void
    {
        $entityManager = $this->createDatabase(app('em'));

        $apiKey =  $entityManager->getRepository(ApiKey::class)
            ->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:regenerate', [
            'name' => 'testing',
        ])->assertExitCode(0);
    }

    public function testInvalidNameThrowsError(): void
    {
        $this->createDatabase(app('em'));

        $this->artisan('apikey:regenerate', [
            'name' => 'test^ing',
        ])->assertExitCode(1);
    }
}

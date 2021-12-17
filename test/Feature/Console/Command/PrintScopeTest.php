<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Console\Command;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;

final class PrintScopeTest extends TestCase
{
    public function testPrintScope(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $scopeRepository = $entityManager->getRepository(Scope::class);
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:print', [
            'name' => $scope->getName(),
        ])->assertExitCode(0);
    }

    public function testPrintAllScopes(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $scopeRepository = $entityManager->getRepository(Scope::class);
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:print', [
        ])->assertExitCode(0);
    }

    public function testErrorOnInvalidName(): void
    {
        $entityManager = app('em');
        $this->createDatabase($entityManager);

        $scopeRepository = $entityManager->getRepository(Scope::class);
        $scope = $scopeRepository->generate('testing');
        $entityManager->flush();

        $this->artisan('apikey:scope:print', [
            'name' => 'invalid',
        ])->assertExitCode(1);
    }
}

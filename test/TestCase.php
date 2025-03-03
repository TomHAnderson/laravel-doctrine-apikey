<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \LaravelDoctrine\ORM\DoctrineServiceProvider::class,
            \ApiSkeletons\Laravel\Doctrine\ApiKey\ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']['doctrine.managers.default.paths'] = [
            __DIR__ . '/Entities'
        ];
    }

    protected function createDatabase(EntityManager $entityManager): EntityManager
    {
        $apiKeyService = app(ApiKeyService::class);

        $apiKeyService->init($entityManager);

        $tool = new SchemaTool($entityManager);
        $tool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        return $entityManager;
    }
}

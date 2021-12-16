<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected EntityManager $entityManager;

    public function setUp(): void
    {
        parent::setUp();
        return;

        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        $config = Setup::createXMLMetadataConfiguration(array(__DIR__ . "/../config/orm"), $isDevMode);

        $conn = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $this->entityManager = EntityManager::create($conn, $config);
        $tool = new SchemaTool($this->entityManager);
        $tool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    protected function getPackageProviders($app)
    {
        return [
            \LaravelDoctrine\ORM\DoctrineServiceProvider::class,
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

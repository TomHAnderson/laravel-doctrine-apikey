<?php

namespace ApiSkeletonsTest\Doctrine\QueryBuilder\Filter;

use ApiSkeletons\Doctrine\QueryBuilder\Filter\Applicator;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

require_once __DIR__ . '/../vendor/autoload.php';

abstract class TestCase extends PHPUnitTestCase
{
    protected EntityManager $entityManager;

    public function setUp(): void
    {
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        $config = Setup::createXMLMetadataConfiguration(array(__DIR__ . "/../config/orm"), $isDevMode);

        $conn = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $this->entityManager = EntityManager::create($conn, $config);
        $tool = new SchemaTool($this->entityManager);
        $res = $tool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }
}


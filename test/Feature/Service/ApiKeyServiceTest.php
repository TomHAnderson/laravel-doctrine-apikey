<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Service;

use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use Doctrine\ORM\EntityManager;

final class ApiKeyServiceTest extends TestCase
{
    public function testInitAndCreateDatabase(): void
    {
        $entityManager = $this->createDatabase(app('em'));

        $this->assertInstanceOf(EntityManager::class, $entityManager);
    }
}

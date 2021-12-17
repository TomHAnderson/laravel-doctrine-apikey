<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\ApiKey\Feature\Entity;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletonsTest\Laravel\Doctrine\ApiKey\TestCase;
use DateTime;

final class AdminEventTest extends TestCase
{
    public function testAdminEvent(): void
    {
        $entityManager = $this->createDatabase(app('em'));
        $repository = $entityManager->getRepository(ApiKey::class);

        $now = new DateTime();

        $apiKey = $repository->generate('testing');
        $entityManager->flush();

        $this->assertGreaterThan(0, $apiKey->getId());
        $this->assertEquals('testing', $apiKey->getName());
        $this->assertEquals(64, strlen($apiKey->getApiKey()));
        $this->assertEquals(true, $apiKey->getIsActive());
        $this->assertGreaterThan($now, $apiKey->getCreatedAt());

        foreach ($apiKey->getAdminEvents() as $adminEvent) {
            $this->assertEquals('generate', $adminEvent->getEvent());
            $this->assertEquals($apiKey, $adminEvent->getApiKey());
            $this->assertEquals(request()->ip(), $adminEvent->getIpAddress());
            $this->assertGreaterThan(0, $adminEvent->getId());
            $this->assertGreaterThan($now, $adminEvent->getCreatedAt());

            $adminEvent->getApiKey()->removeAdminEvent($adminEvent);
        }

        $this->assertEquals(0, count($apiKey->getAdminEvents()));
    }
}

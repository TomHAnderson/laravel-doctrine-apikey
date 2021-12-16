<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;

class ApiKeyService
{
    private EntityManager $entityManager;

    public function init(EntityManager $entityManager): self
    {
        $this->entityManager = $entityManager;

        $driver = new XmlDriver(__DIR__ . '/../../config/orm');

        $this->entityManager->getConfiguration()
            ->getMetadataDriverImpl()
            ->addDriver($driver, 'ApiSkeletons\Laravel\Doctrine\ApiKey\Entity');

        return $this;
    }
}

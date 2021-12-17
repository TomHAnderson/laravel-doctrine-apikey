<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Service;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;

class ApiKeyService
{
    private ?EntityManager $entityManager = null;

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function init(EntityManager $entityManager): self|bool
    {
        if  (! is_null($this->entityManager)) {
            return false;
        }

        $driver = new XmlDriver(__DIR__ . '/../../config/orm');

        $entityManager
            ->getConfiguration()
            ->getMetadataDriverImpl()
            ->addDriver($driver, 'ApiSkeletons\Laravel\Doctrine\ApiKey\Entity')
            ;

        $this->entityManager = $entityManager;

        return $this;
    }

    public function isActive(string $key): bool
    {
        $apiKey = $this->entityManager->getRepository(ApiKey::class)
            ->findOneBy([
                'key' => $key,
            ]);

        if (! $apiKey) {
            return false;
        }

        return $apiKey->getIsActive();
    }

    public function hasScope(string $key, string $scopeName): bool
    {
        $apiKey = $this->entityManager->getRepository(ApiKey::class)
            ->findOneBy([
                'key' => $key,
            ]);

        $scope = $this->entityManager->getRepository(Scope::class)
            ->findOneBy([
                'name' => $scopeName,
            ]);

        if (! $apiKey || ! $scope) {
            return false;
        }

        $found = false;
        foreach ($apiKey->getScopes() as $s) {
            if ($scope === $s) {
                $found = true;
                break;
            }
        }

        return $found;
    }

}

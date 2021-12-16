<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Str;

class ApiKeyRepository extends EntityRepository
{
    public function create($name): ApiKey
    {
        // Verify name is unique
        $apiKeys = $this->findBy([
            'name' => $name,
        ]);

        if ($apiKeys) {
            throw new Exception('ApiKey with name "' . $name . '" already exists.');
        }

        do {
            $key = Str::random(64);
        } while ($this->findBy(['key' => $key]));

        $apiKey = new ApiKey();
        $apiKey
            ->setName($name)
            ->setKey($key)
            ->setCreatedAt(new DateTime())
            ->setIsDeleted(false)
        ;

        $this->getEntityManager()->persist($apiKey);

        return $apiKey;
    }

    public function delete(ApiKey $apiKey): ApiKey
    {
        if ($apiKey->getDeletedAt() || $apiKey->getIsDeleted()) {
            throw new Exception('ApiKey is already marked as deleted');
        }

        $apiKey
            ->setIsDeleted(true)
            ->setDeletedAt(new DateTime())
            ;

        return $apiKey;
    }
}

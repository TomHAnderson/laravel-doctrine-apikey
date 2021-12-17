<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ApiKeyDoesNotHaveScope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateScopeForApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Str;

class ApiKeyRepository extends EntityRepository
{
    public function generate($name): ApiKey
    {
        // Verify name is unique
        $apiKeys = $this->findBy([
            'name' => $name,
        ]);

        if ($apiKeys) {
            throw new DuplicateName('An API key already exists with the name: ' . $name);
        }

        if (! $this->isValidName($name)) {
            throw new InvalidName('Please provide a valid name: [a-z0-9-]');
        }

        do {
            $key = Str::random(64);
        } while ($this->findBy(['key' => $key]));

        $apiKey = new ApiKey();
        $apiKey
            ->setName($name)
            ->setKey($key)
            ->setCreatedAt(new DateTime())
            ->setIsActive(true)
            ->setStatusAt(new DateTime())
        ;

        $this->getEntityManager()->persist($apiKey);

        return $apiKey;
    }

    public function updateActive(ApiKey $apiKey, bool $status): ApiKey
    {
        $apiKey
            ->setIsActive($status)
            ->setStatusAt(new DateTime())
            ;

        return $apiKey;
    }

    public function addScope(ApiKey $apiKey, Scope $scope): ApiKey
    {
        // Do not add scopes twice
        foreach ($apiKey->getScopes() as $s) {
            if ($s === $scope) {
                throw new DuplicateScopeForApiKey('ApiKey already has requested scope');
            }
        }

        $apiKey->addScope($scope);
        $scope->addApiKey($apiKey);

        return $apiKey;
    }

    public function removeScope(ApiKey $apiKey, Scope $scope): ApiKey
    {
        $found = false;
        foreach ($apiKey->getScopes() as $s) {
            if ($s === $scope) {
                $found = true;
                break;
            }
        }

        if (! $found) {
            throw new ApiKeyDoesNotHaveScope(
                'The requested Scope to remove does not exist on the ApiKey'
            );
        }

        $apiKey->removeScope($scope);
        $scope->removeApiKey($apiKey);

        return $apiKey;
    }

    public function isValidName($name): bool
    {
        return (bool) preg_match('/^[a-z0-9-]{1,255}$/', $name);
    }
}

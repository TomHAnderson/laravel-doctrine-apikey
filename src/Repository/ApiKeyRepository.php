<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AdminEvent;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\ApiKeyDoesNotHaveScope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateScopeForApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Str;

use function preg_match;

class ApiKeyRepository extends EntityRepository
{
    public function generate(string $name): ApiKey
    {
        // Verify name is unique
        $apiKeys = $this->findBy(['name' => $name]);

        if ($apiKeys) {
            throw new DuplicateName('An API key already exists with the name: ' . $name);
        }

        if (!$this->isValidName($name)) {
            throw new InvalidName('Please provide a valid name: [a-z0-9-]');
        }

        do {
            $key = Str::random(64);
        } while ($this->findBy(['api_key' => $key]));

        $apiKey = new ApiKey();
        $apiKey
            ->setName($name)
            ->setApiKey($key)
            ->setCreatedAt(new DateTime())
            ->setIsActive(true)
            ->setStatusAt(new DateTime());

        $this->getEntityManager()->persist($apiKey);
        $this->getEntityManager()->persist($this->logAdminEvent($apiKey,'generate'));

        return $apiKey;
    }

    public function updateActive(ApiKey $apiKey, bool $status): ApiKey
    {
        $apiKey
            ->setIsActive($status)
            ->setStatusAt(new DateTime());

        $eventName = ($status) ? 'activate': 'deactivate';
        $this->getEntityManager()->persist($this->logAdminEvent($apiKey, $eventName));

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

        $this->getEntityManager()->persist($this->logAdminEvent($apiKey, 'add scope: ' . $scope->getName()));

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

        if (!$found) {
            throw new ApiKeyDoesNotHaveScope(
                'The requested Scope to remove does not exist on the ApiKey'
            );
        }

        $apiKey->removeScope($scope);
        $scope->removeApiKey($apiKey);

        $this->getEntityManager()->persist($this->logAdminEvent($apiKey, 'remove scope: ' . $scope->getName()));

        return $apiKey;
    }

    public function isValidName(string $name): bool
    {
        return (bool)preg_match('/^[a-z0-9-]{1,255}$/', $name);
    }

    protected function logAdminEvent(ApiKey $apiKey, string $eventName)
    {
        return (new AdminEvent())
            ->setIpAddress(request()->ip())
            ->setApiKey($apiKey)
            ->setEvent($eventName)
            ->setCreatedAt(new DateTime())
            ;
    }
}

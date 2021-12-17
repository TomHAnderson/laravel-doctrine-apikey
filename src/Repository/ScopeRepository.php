<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\DuplicateName;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Exception\InvalidName;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Exception;

class ScopeRepository extends EntityRepository
{
    public function generate($name): Scope
    {
        // Verify name is unique
        $scopes = $this->findBy([
            'name' => $name,
        ]);

        if ($scopes) {
            throw new DuplicateName('A Scope already exists with the name: ' . $name);
        }

        if (! $this->isValidName($name)) {
            throw new InvalidName('Please provide a valid name: [a-z0-9-]');
        }

        $scope = new Scope();
        $scope
            ->setName($name)
            ->setCreatedAt(new DateTime())
            ;

        $this->getEntityManager()->persist($scope);

        return $scope;
    }

    public function canDelete(Scope $scope): bool
    {
        return ! (bool) sizeof($scope->getApiKeys());
    }

    public function delete(Scope $scope): Scope|bool
    {
        if (! $this->canDelete($scope)) {
            return false;
        }

        $this->getEntityManager()->remove($scope);

        return $scope;
    }

    public function isValidName($name): bool
    {
        return (bool) preg_match('/^[a-z0-9-]{1,255}$/', $name);
    }
}

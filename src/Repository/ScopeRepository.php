<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Repository;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey;
use ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Exception;

class ScopeRepository extends EntityRepository
{
    public function create($name): Scope
    {
        // Verify name is unique
        $scopes = $this->findBy([
            'name' => $name,
        ]);

        if ($scopes) {
            throw new Exception('Scope with name "' . $name . '" already exists.');
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

    public function delete(Scope $scope): void
    {
        if (! $this->canDelete($scope)) {
            throw new Exception('Cannot delete scope while ApiKeys are using it.');
        }

        $this->getEntityManager()->remove($scope);
    }
}

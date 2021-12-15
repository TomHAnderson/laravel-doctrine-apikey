<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey;

/**
 * ApiKey
 */
class ApiKey
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $key;

    /**
     * @var bool
     */
    private $is_deleted;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime|null
     */
    private $deleted_at;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $accessEvents;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $adminEvents;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $scopes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accessEvents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->adminEvents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->scopes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return ApiKey
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set key.
     *
     * @param string $key
     *
     * @return ApiKey
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set isDeleted.
     *
     * @param bool $isDeleted
     *
     * @return ApiKey
     */
    public function setIsDeleted($isDeleted)
    {
        $this->is_deleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted.
     *
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return ApiKey
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return ApiKey
     */
    public function setDeletedAt($deletedAt = null)
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add accessEvent.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\AccessEvent $accessEvent
     *
     * @return ApiKey
     */
    public function addAccessEvent(\ApiSkeletons\Laravel\Doctrine\ApiKey\AccessEvent $accessEvent)
    {
        $this->accessEvents[] = $accessEvent;

        return $this;
    }

    /**
     * Remove accessEvent.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\AccessEvent $accessEvent
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAccessEvent(\ApiSkeletons\Laravel\Doctrine\ApiKey\AccessEvent $accessEvent)
    {
        return $this->accessEvents->removeElement($accessEvent);
    }

    /**
     * Get accessEvents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccessEvents()
    {
        return $this->accessEvents;
    }

    /**
     * Add adminEvent.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\AdminEvent $adminEvent
     *
     * @return ApiKey
     */
    public function addAdminEvent(\ApiSkeletons\Laravel\Doctrine\ApiKey\AdminEvent $adminEvent)
    {
        $this->adminEvents[] = $adminEvent;

        return $this;
    }

    /**
     * Remove adminEvent.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\AdminEvent $adminEvent
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAdminEvent(\ApiSkeletons\Laravel\Doctrine\ApiKey\AdminEvent $adminEvent)
    {
        return $this->adminEvents->removeElement($adminEvent);
    }

    /**
     * Get adminEvents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdminEvents()
    {
        return $this->adminEvents;
    }

    /**
     * Add scope.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Scope $scope
     *
     * @return ApiKey
     */
    public function addScope(\ApiSkeletons\Laravel\Doctrine\ApiKey\Scope $scope)
    {
        $this->scopes[] = $scope;

        return $this;
    }

    /**
     * Remove scope.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Scope $scope
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeScope(\ApiSkeletons\Laravel\Doctrine\ApiKey\Scope $scope)
    {
        return $this->scopes->removeElement($scope);
    }

    /**
     * Get scopes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScopes()
    {
        return $this->scopes;
    }
}

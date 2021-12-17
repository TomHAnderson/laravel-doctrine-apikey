<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Entity;

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
    private $api_key;

    /**
     * @var bool
     */
    private $is_active;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $status_at;

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
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return ApiKey
     */
    public function setApiKey($apiKey)
    {
        $this->api_key = $apiKey;

        return $this;
    }

    /**
     * Get apiKey.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return ApiKey
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->is_active;
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
     * Set statusAt.
     *
     * @param \DateTime $statusAt
     *
     * @return ApiKey
     */
    public function setStatusAt($statusAt)
    {
        $this->status_at = $statusAt;

        return $this;
    }

    /**
     * Get statusAt.
     *
     * @return \DateTime
     */
    public function getStatusAt()
    {
        return $this->status_at;
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
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AccessEvent $accessEvent
     *
     * @return ApiKey
     */
    public function addAccessEvent(\ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AccessEvent $accessEvent)
    {
        $this->accessEvents[] = $accessEvent;

        return $this;
    }

    /**
     * Remove accessEvent.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AccessEvent $accessEvent
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAccessEvent(\ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AccessEvent $accessEvent)
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
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AdminEvent $adminEvent
     *
     * @return ApiKey
     */
    public function addAdminEvent(\ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AdminEvent $adminEvent)
    {
        $this->adminEvents[] = $adminEvent;

        return $this;
    }

    /**
     * Remove adminEvent.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AdminEvent $adminEvent
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAdminEvent(\ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\AdminEvent $adminEvent)
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
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope $scope
     *
     * @return ApiKey
     */
    public function addScope(\ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope $scope)
    {
        $this->scopes[] = $scope;

        return $this;
    }

    /**
     * Remove scope.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope $scope
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeScope(\ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\Scope $scope)
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

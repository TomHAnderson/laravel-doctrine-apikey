<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Entity;

/**
 * Scope
 */
class Scope
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $apiKeys;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiKeys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Scope
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Scope
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add apiKey.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\ApiKey $apiKey
     *
     * @return Scope
     */
    public function addApiKey(\ApiSkeletons\Laravel\Doctrine\ApiKey\ApiKey $apiKey)
    {
        $this->apiKeys[] = $apiKey;

        return $this;
    }

    /**
     * Remove apiKey.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\ApiKey $apiKey
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeApiKey(\ApiSkeletons\Laravel\Doctrine\ApiKey\ApiKey $apiKey)
    {
        return $this->apiKeys->removeElement($apiKey);
    }

    /**
     * Get apiKeys.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApiKeys()
    {
        return $this->apiKeys;
    }
}

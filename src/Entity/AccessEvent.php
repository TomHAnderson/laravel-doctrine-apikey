<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Entity;

/**
 * AccessEvent
 */
class AccessEvent
{
    /**
     * @var string
     */
    private $ip_address;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey
     */
    private $apiKey;


    /**
     * Set ipAddress.
     *
     * @param string $ipAddress
     *
     * @return AccessEvent
     */
    public function setIpAddress($ipAddress)
    {
        $this->ip_address = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress.
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return AccessEvent
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return AccessEvent
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
     * Set apiKey.
     *
     * @param \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey $apiKey
     *
     * @return AccessEvent
     */
    public function setApiKey(\ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey $apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey.
     *
     * @return \ApiSkeletons\Laravel\Doctrine\ApiKey\Entity\ApiKey
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}

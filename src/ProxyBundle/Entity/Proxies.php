<?php
namespace ProxyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="proxies")
 * @ORM\Entity
 */
class Proxies
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=15, unique=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="string",length=10)
     */
    private $port;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     */
    private $isSsl;

    /**
     * @ORM\Column(type="string")
     */
    private $check_timestamp;

    /**
     * @ORM\Column(type="string")
     */
    private $country_code;

    /**
     * @ORM\Column(type="string")
     */
    private $latency;

    /**
     * @ORM\Column(type="string")
     */
    private $reliability;

    /**
     * @ORM\Column(type="string")
     */
    private $isOnline;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateChecked;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $source;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Proxies
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set port
     *
     * @param string $port
     * @return Proxies
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Proxies
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set ssl
     *
     * @param string $isSsl
     * @return Proxies
     */
    public function setIsSsl($isSsl)
    {
        $this->isSsl = $isSsl;

        return $this;
    }

    /**
     * Get ssl
     *
     * @return string
     */
    public function getIsSsl()
    {
        return $this->isSsl;
    }

    /**
     * Set check_timestamp
     *
     * @param string $checkTimestamp
     * @return Proxies
     */
    public function setCheckTimestamp($checkTimestamp)
    {
        $this->check_timestamp = $checkTimestamp;

        return $this;
    }

    /**
     * Get check_timestamp
     *
     * @return string
     */
    public function getCheckTimestamp()
    {
        return $this->check_timestamp;
    }

    /**
     * Set country_code
     *
     * @param string $countryCode
     * @return Proxies
     */
    public function setCountryCode($countryCode)
    {
        $this->country_code = $countryCode;

        return $this;
    }

    /**
     * Get country_code
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * Set latency
     *
     * @param string $latency
     * @return Proxies
     */
    public function setLatency($latency)
    {
        $this->latency = $latency;

        return $this;
    }

    /**
     * Get latency
     *
     * @return string
     */
    public function getLatency()
    {
        return $this->latency;
    }

    /**
     * Set reliability
     *
     * @param string $reliability
     * @return Proxies
     */
    public function setReliability($reliability)
    {
        $this->reliability = $reliability;

        return $this;
    }

    /**
     * Get reliability
     *
     * @return string
     */
    public function getReliability()
    {
        return $this->reliability;
    }

    /**
     * Set isOnline
     *
     * @param string $isOnline
     * @return Proxies
     */
    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    /**
     * Get isOnline
     *
     * @return string
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }


    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Proxies
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set dateChecked
     *
     * @param \DateTime $dateChecked
     * @return Proxies
     */
    public function setDateChecked($dateChecked)
    {
        $this->dateChecked = $dateChecked;

        return $this;
    }

    /**
     * Get dateChecked
     *
     * @return \DateTime
     */
    public function getDateChecked()
    {
        return $this->dateChecked;
    }

    /**
     * Set source
     *
     * @param \string $source
     * @return Proxies
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \string
     */
    public function getSource()
    {
        return $this->source;
    }
}

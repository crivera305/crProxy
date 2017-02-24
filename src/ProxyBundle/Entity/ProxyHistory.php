<?php
namespace ProxyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="proxyHistory")
 * @ORM\Entity
 */
class ProxyHistory
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=15, unique=false)
     */
    private $ip;

    /**
     * @ORM\Column(type="string")
     */
    private $latency;
    /**
     * @ORM\Column(type="string")
     */
    private $isOnline;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateChecked;


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
     * @return ProxyHistory
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
     * Set latency
     *
     * @param string $latency
     * @return ProxyHistory
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
     * Set isOnline
     *
     * @param string $isOnline
     * @return ProxyHistory
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
     * Set dateChecked
     *
     * @param \DateTime $dateChecked
     * @return ProxyHistory
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
}

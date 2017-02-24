<?php
namespace XpageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="videos")
 * @ORM\Entity
 */
class Videos
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
    private $mediaId;

    /**
     * @ORM\Column(type="string",length=10)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     */
    private $file;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $source;

}

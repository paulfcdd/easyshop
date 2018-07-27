<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="templates")
 * @ORM\HasLifecycleCallbacks()
 */
class Template
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column()
     */
    private $name;

    /**
     * @var string
     * @ORM\Column()
     */
    private $sitePart;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateUploaded;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Template
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSitePart()
    {
        return $this->sitePart;
    }

    /**
     * @param string $sitePart
     * @return Template
     */
    public function setSitePart($sitePart)
    {
        $this->sitePart = $sitePart;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return Template
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateUploaded()
    {
        return $this->dateUploaded;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setDateUploaded()
    {
        $this->dateUploaded = new \DateTime();
    }


}
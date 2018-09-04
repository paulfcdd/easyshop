<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class SpecificationGroup
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="specifications_groups")
 */
class SpecificationGroup
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Specification", mappedBy="specificationGroup")
     */
    private $specifications;

    public function __construct()
    {
        $this->specifications = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SpecificationGroup
     */
    public function setName(string $name): SpecificationGroup
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\Specification $specification
     *
     * @return $this
     */
    public function addSpecification(Specification $specification)
    {
        if (!$this->specifications->contains($specification))
        {
            $this->specifications->add($specification);
        }

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Specification $specification
     *
     * @return $this
     */
    public function removeSpecification(Specification $specification)
    {
        if ($this->specifications->contains($specification))
        {
            $this->specifications->remove($specification);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }
}
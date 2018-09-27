<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Specification
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="specifications")
 */
class Specification
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SpecificationGroup", inversedBy="specifications")
     * @ORM\JoinColumn(name="specification_group_id", referencedColumnName="id")
     */
    private $specificationGroup;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductSpecification", mappedBy="specification")
     */
    private $productSpecifications;

    public function __construct()
    {
        $this->productSpecifications = new ArrayCollection();
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
     * @return Specification
     */
    public function setName(string $name): Specification
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecificationGroup()
    {
        return $this->specificationGroup;
    }

    /**
     * @param mixed $specificationGroup
     * @return Specification
     */
    public function setSpecificationGroup($specificationGroup)
    {
        $this->specificationGroup = $specificationGroup;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\ProductSpecification $productSpecification
     * @return $this
     */
    public function addProductSpecification(ProductSpecification $productSpecification) {

        if (!$this->productSpecifications->contains($productSpecification))
        {
            $this->productSpecifications->add($productSpecification);
        }

        return $this;
    }

    public function removeProductSpecification(ProductSpecification $productSpecification) {
        if ($this->productSpecifications->contains($productSpecification))
        {
            $this->productSpecifications->remove($productSpecification);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductSpecifications()
    {
        return $this->productSpecifications;
    }
}
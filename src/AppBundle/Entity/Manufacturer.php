<?php


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Traits;

/**
 * Class Manufacturer
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="manufacturers")
 */
class Manufacturer
{
    use Traits\SeoTrait;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="manufacturer")
     */
    private $products;

    /**
     * Manufacturer constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * @return Manufacturer
     */
    public function setName(string $name): Manufacturer
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\Product $product
     * @return $this
     */
    public function addProduct(Product $product)
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Product $product
     * @return $this
     */
    public function removeProduct(Product $product)
    {
        if ($this->products->contains($product)) {
            $this->products->remove($product);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }
}
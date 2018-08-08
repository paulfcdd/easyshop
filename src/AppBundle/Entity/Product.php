<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits as Traits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
{
    use Traits\SeoTrait;

    public const STATUSES = [
        1 => 'Enabled',
        0 => 'Disabled'
    ];

    public const FEATURED = [
        1 => 'Yes',
        0 => 'No'
    ];

    public const MINIMUM_QUANTITY = 1;

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
    private $model;

    /**
     * @var string
     * @ORM\Column(length=500)
     */
    private $description;

//    /**
//     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Tag", mappedBy="product")
//     */
//    private $tags;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Manufacturer", inversedBy="products")
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id")
     */
    private $manufacturer;

    /**
     * @var integer
     * @ORM\Column(type="integer", length=5)
     */
    private $quantity;

    /**
     * @var integer
     * @ORM\Column(type="integer", length=5)
     */
    private $minimumQuantity = self::MINIMUM_QUANTITY;

    /**
     * @var integer
     * @ORM\Column(type="integer", length=1)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $featured;

    /*ManyToMany to Attribute entity*/
//    private $attributes;

    /**
     * Product constructor.
     */
    public function __construct()
    {
//        $this->tags = new ArrayCollection();
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
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return Product
     */
    public function setModel(string $model): Product
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Product
     */
    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinimumQuantity(): int
    {
        return $this->minimumQuantity;
    }

    /**
     * @param int $minimumQuantity
     * @return Product
     */
    public function setMinimumQuantity(int $minimumQuantity = self::MINIMUM_QUANTITY): Product
    {
        $this->minimumQuantity = $minimumQuantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Product
     */
    public function setStatus(int $status): Product
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param mixed $manufacturer
     * @return Product
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    /**
     * @param bool $featured
     * @return Product
     */
    public function setFeatured(bool $featured): Product
    {
        $this->featured = $featured;
        return $this;
    }


//    /**
//     * @param \AppBundle\Entity\Tag $tag
//     * @return $this
//     */
//    public function addTag(Tag $tag)
//    {
//        if (!$this->tags->contains($tag))
//        {
//            $this->tags->add($tag);
//        }
//
//        return $this;
//    }
//
//    /**
//     * @param \AppBundle\Entity\Tag $tag
//     * @return $this
//     */
//    public function removeTag(Tag $tag)
//    {
//        if ($this->tags->contains($tag))
//        {
//            $this->tags->remove($tag);
//        }
//
//        return $this;
//    }
//
//    /**
//     * @return \Doctrine\Common\Collections\ArrayCollection
//     */
//    public function getTags()
//    {
//        return $this->tags;
//    }
}
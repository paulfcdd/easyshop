<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="id")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     *
     */
    private $parent;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $showInMenu;

    /**
     * @var string | null
     * @ORM\Column(nullable=true)
     */
    private $metaTitle;

    /**
     * @var string | null
     * @ORM\Column(nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string | null
     * @ORM\Column(nullable=true)
     */
    private $metaDescription;

    /**
     * @return int
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
     * @return Category
     */
    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     * @return Category
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setDateCreated()
    {
        $this->dateCreated = new \DateTime();
    }

    /**
     * @return bool | null
     */
    public function isShowInMenu(): ?bool
    {
        return $this->showInMenu;
    }

    /**
     * @param bool $showInMenu
     * @return Category
     */
    public function setShowInMenu(bool $showInMenu): Category
    {
        $this->showInMenu = $showInMenu;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * @param null|string $metaTitle
     * @return Category
     */
    public function setMetaTitle(?string $metaTitle): Category
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    /**
     * @param null|string $metaKeywords
     * @return Category
     */
    public function setMetaKeywords(?string $metaKeywords): Category
    {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * @param null|string $metaDescription
     * @return Category
     */
    public function setMetaDescription(?string $metaDescription): Category
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }
}
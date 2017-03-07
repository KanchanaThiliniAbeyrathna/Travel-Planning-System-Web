<?php

namespace App1\ExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelCategory
 *
 * @ORM\Table(name="hotel_category", uniqueConstraints={@ORM\UniqueConstraint(name="idhotel_category_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class HotelCategory
{
    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=45, nullable=false)
     */
    private $categoryName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return HotelCategory
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return HotelCategory
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

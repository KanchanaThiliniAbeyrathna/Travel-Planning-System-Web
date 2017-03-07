<?php

namespace App1\ExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Privillege
 *
 * @ORM\Table(name="privillege", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class Privillege
{
    /**
     * @var string
     *
     * @ORM\Column(name="privillege", type="string", length=45, nullable=false)
     */
    private $privillege;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
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
     * Set privillege
     *
     * @param string $privillege
     *
     * @return Privillege
     */
    public function setPrivillege($privillege)
    {
        $this->privillege = $privillege;

        return $this;
    }

    /**
     * Get privillege
     *
     * @return string
     */
    public function getPrivillege()
    {
        return $this->privillege;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Privillege
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

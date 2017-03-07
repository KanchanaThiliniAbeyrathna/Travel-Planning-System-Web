<?php

namespace App1\ExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContactNumber
 *
 * @ORM\Table(name="contact_number", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_contact_number_visitingplace1_idx", columns={"visitingplace_id"}), @ORM\Index(name="fk_contact_number_hotel1_idx", columns={"hotel_id"})})
 * @ORM\Entity
 */
class ContactNumber
{
    /**
     * @var string
     *
     * @ORM\Column(name="contact_number", type="string", length=12, nullable=false)
     */
    private $contactNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \App1\ExampleBundle\Entity\Visitingplace
     *
     * @ORM\ManyToOne(targetEntity="App1\ExampleBundle\Entity\Visitingplace")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visitingplace_id", referencedColumnName="id")
     * })
     */
    private $visitingplace;

    /**
     * @var \App1\ExampleBundle\Entity\Hotel
     *
     * @ORM\ManyToOne(targetEntity="App1\ExampleBundle\Entity\Hotel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hotel_id", referencedColumnName="id")
     * })
     */
    private $hotel;



    /**
     * Set contactNumber
     *
     * @param string $contactNumber
     *
     * @return ContactNumber
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    /**
     * Get contactNumber
     *
     * @return string
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
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

    /**
     * Set visitingplace
     *
     * @param \App1\ExampleBundle\Entity\Visitingplace $visitingplace
     *
     * @return ContactNumber
     */
    public function setVisitingplace(\App1\ExampleBundle\Entity\Visitingplace $visitingplace = null)
    {
        $this->visitingplace = $visitingplace;

        return $this;
    }

    /**
     * Get visitingplace
     *
     * @return \App1\ExampleBundle\Entity\Visitingplace
     */
    public function getVisitingplace()
    {
        return $this->visitingplace;
    }

    /**
     * Set hotel
     *
     * @param \App1\ExampleBundle\Entity\Hotel $hotel
     *
     * @return ContactNumber
     */
    public function setHotel(\App1\ExampleBundle\Entity\Hotel $hotel = null)
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * Get hotel
     *
     * @return \App1\ExampleBundle\Entity\Hotel
     */
    public function getHotel()
    {
        return $this->hotel;
    }
}

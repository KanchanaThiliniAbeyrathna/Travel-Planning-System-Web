<?php

namespace App1\ExampleBundle\Entity;

/**
 * Trip
 */
class Trip
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \App1\ExampleBundle\Entity\City
     */
    private $city;

    /**
     * @var \App1\ExampleBundle\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $visitingplace;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $hotel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitingplace = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hotel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Trip
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Set city
     *
     * @param \App1\ExampleBundle\Entity\City $city
     *
     * @return Trip
     */
    public function setCity(\App1\ExampleBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \App1\ExampleBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set user
     *
     * @param \App1\ExampleBundle\Entity\User $user
     *
     * @return Trip
     */
    public function setUser(\App1\ExampleBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \App1\ExampleBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add visitingplace
     *
     * @param \App1\ExampleBundle\Entity\Visitingplace $visitingplace
     *
     * @return Trip
     */
    public function addVisitingplace(\App1\ExampleBundle\Entity\Visitingplace $visitingplace)
    {
        $this->visitingplace[] = $visitingplace;

        return $this;
    }

    /**
     * Remove visitingplace
     *
     * @param \App1\ExampleBundle\Entity\Visitingplace $visitingplace
     */
    public function removeVisitingplace(\App1\ExampleBundle\Entity\Visitingplace $visitingplace)
    {
        $this->visitingplace->removeElement($visitingplace);
    }

    /**
     * Get visitingplace
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitingplace()
    {
        return $this->visitingplace;
    }

    /**
     * Add hotel
     *
     * @param \App1\ExampleBundle\Entity\Hotel $hotel
     *
     * @return Trip
     */
    public function addHotel(\App1\ExampleBundle\Entity\Hotel $hotel)
    {
        $this->hotel[] = $hotel;

        return $this;
    }

    /**
     * Remove hotel
     *
     * @param \App1\ExampleBundle\Entity\Hotel $hotel
     */
    public function removeHotel(\App1\ExampleBundle\Entity\Hotel $hotel)
    {
        $this->hotel->removeElement($hotel);
    }

    /**
     * Get hotel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHotel()
    {
        return $this->hotel;
    }
}

<?php

namespace App1\ExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App1\ExampleBundle\Modal\UploadFileMover;
/**
 * Hotel
 *
 * @ORM\Table(name="hotel", uniqueConstraints={@ORM\UniqueConstraint(name="hotel_id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_hotel_hotel_category1_idx", columns={"hotel_category_id"}), @ORM\Index(name="fk_hotel_city1_idx", columns={"city_id"})})
 * @ORM\Entity
 */
class Hotel
{

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=200, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=0, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=100, nullable=false)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="latitude", type="float", length=20, nullable=true)
     */
    private $latitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="longitude", type="float", length=20, nullable=true)
     */
    private $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \App1\ExampleBundle\Entity\HotelCategory
     *
     * @ORM\ManyToOne(targetEntity="App1\ExampleBundle\Entity\HotelCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hotel_category_id", referencedColumnName="id")
     * })
     */
    private $hotelCategory;

    /**
     * @var \App1\ExampleBundle\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="App1\ExampleBundle\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    private $city;

    private $file;
    
    private $subDir;
    
    private $filePersistencePath;
    
    /** @var string */
    protected static $uploadDirectory = 'public/uploads';

    static public function setUploadDirectory($dir)
    {
        self::$uploadDirectory = $dir;
    }
    
    static public function getUploadDirectory()
    {
        if (self::$uploadDirectory === null) {
            throw new \RuntimeException("Trying to access upload directory for profile files");
        }
        return self::$uploadDirectory;
    }
    public function setSubDirectory($dir)
    {
         $this->subDir = $dir;
    }
    
    public function getSubDirectory()
    {
        if ($this->subDir === null) {
            throw new \RuntimeException("Trying to access sub directory for profile files");
        }
        return $this->subDir;
    }
    
   
    public function setFile(File $file)
    {
        $this->file = $file;
    }
    
    public function getFile()
    {
        return new File(self::getUploadDirectory() . "/" . $this->filePersistencePath);
    }
    
     public function getOriginalFileName()
    {
        return $this->file->getClientOriginalName();
    }
    
    public function getFilePersistencePath()
    {
        return $this->filePersistencePath;
    }
    
    public function processFile()
    {
        if (! ($this->file instanceof UploadedFile) ) {
            return false;
        }
        $uploadFileMover = new UploadFileMover();
        $this->filePersistencePath = $uploadFileMover->moveUploadedFile($this->file, self::getUploadDirectory(),$this->subDir);
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Hotel
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Hotel
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
     * Set path
     *
     * @param string $path
     *
     * @return Hotel
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Hotel
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Hotel
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Hotel
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

    /**
     * Set hotelCategory
     *
     * @param \App1\ExampleBundle\Entity\HotelCategory $hotelCategory
     *
     * @return Hotel
     */
    public function setHotelCategory(\App1\ExampleBundle\Entity\HotelCategory $hotelCategory = null)
    {
        $this->hotelCategory = $hotelCategory;

        return $this;
    }

    /**
     * Get hotelCategory
     *
     * @return \App1\ExampleBundle\Entity\HotelCategory
     */
    public function getHotelCategory()
    {
        return $this->hotelCategory;
    }

    /**
     * Set city
     *
     * @param \App1\ExampleBundle\Entity\City $city
     *
     * @return Hotel
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $trip;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->trip = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add trip
     *
     * @param \App1\ExampleBundle\Entity\Trip $trip
     *
     * @return Hotel
     */
    public function addTrip(\App1\ExampleBundle\Entity\Trip $trip)
    {
        $this->trip[] = $trip;

        return $this;
    }

    /**
     * Remove trip
     *
     * @param \App1\ExampleBundle\Entity\Trip $trip
     */
    public function removeTrip(\App1\ExampleBundle\Entity\Trip $trip)
    {
        $this->trip->removeElement($trip);
    }

    /**
     * Get trip
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrip()
    {
        return $this->trip;
    }
    
    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Hotel
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Hotel
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
}


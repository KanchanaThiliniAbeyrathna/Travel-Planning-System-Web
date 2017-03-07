<?php

namespace App1\ExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App1\ExampleBundle\Modal\UploadFileMover;

/**
 * Visitingplace
 *
 * @ORM\Table(name="visitingplace", uniqueConstraints={@ORM\UniqueConstraint(name="place_id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_visitingplace_place_category1_idx", columns={"place_category_id"}), @ORM\Index(name="fk_visitingplace_city1_idx", columns={"city_id"})})
 * @ORM\Entity
 */
class Visitingplace
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
     * @ORM\Column(name="images", type="string", length=100, nullable=true)
     */
    private $path;

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
     * @var \App1\ExampleBundle\Entity\PlaceCategory
     *
     * @ORM\ManyToOne(targetEntity="App1\ExampleBundle\Entity\PlaceCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="place_category_id", referencedColumnName="id")
     * })
     */
    private $placeCategory;

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
     * @return Visitingplace
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
     * @return Visitingplace
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
     * @return Visitingplace
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
     * Set description
     *
     * @param string $description
     *
     * @return Visitingplace
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
     * Set placeCategory
     *
     * @param \App1\ExampleBundle\Entity\PlaceCategory $placeCategory
     *
     * @return Visitingplace
     */
    public function setPlaceCategory(\App1\ExampleBundle\Entity\PlaceCategory $placeCategory = null)
    {
        $this->placeCategory = $placeCategory;

        return $this;
    }

    /**
     * Get placeCategory
     *
     * @return \App1\ExampleBundle\Entity\PlaceCategory
     */
    public function getPlaceCategory()
    {
        return $this->placeCategory;
    }

    /**
     * Set city
     *
     * @param \App1\ExampleBundle\Entity\City $city
     *
     * @return Visitingplace
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
     * @return Visitingplace
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
     * @return Visitingplace
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
     * @return Visitingplace
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

<?php

namespace AppBundle\Entity;

use AppBundle\Controller\AnimalController;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Client;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Animal
 *
 * @ORM\Table(name="animal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnimalRepository")
 */
class Animal
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Many Animal for One Client.
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="animals")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="species", type="string", length=255)
     */
    private $species;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAlive", type="boolean")
     */
    private $isAlive;

    /**
     * @var bool
     *
     * @ORM\Column(name="isGoingOutside", type="boolean")
     */
    private $isGoingOutside;

    /**
     * @var string
     *
     * @ORM\Column(name="vaccination", type="text", nullable=true)
     */
    private $vaccination;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set year
     *
     * @param Client $client
     *
     * @return Client
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }


    /**
     * Set species
     *
     * @param string $species
     *
     * @return Animal
     */
    public function setSpecies($species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return string
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Animal
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Animal
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
     * Set notes
     *
     * @param string $notes
     *
     * @return Animal
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Set Set IsGoingOutside
     *
     * @return Animal
     */
    public function setIsGoingOutside($isGoingOutside)
    {
        $this->isGoingOutside = $isGoingOutside;

    }
    /**
     * Is going outside
     *
     * @return Boolean
     */
    public function isGoingOutside()
    {
        return $this->isGoingOutside;

    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set isAlive
     *
     * @param boolean $isAlive
     *
     * @return Animal
     */
    public function setIsAlive($isAlive)
    {
        $this->isAlive = $isAlive;

        return $this;
    }

    /**
     * Get isAlive
     *
     * @return bool
     */
    public function getIsAlive()
    {
        return $this->isAlive;
    }

    /**
     * Set vaccination
     *
     * @param string $vaccination
     *
     * @return Animal
     */
    public function setVaccination($vaccination)
    {
        $this->vaccination = $vaccination;

        return $this;
    }

    /**
     * Get vaccination
     *
     * @return string
     */
    public function getVaccination()
    {
        return $this->vaccination;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Client
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Client
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    public function __toString(){
        $tostring = $this->name;
        $tostring .= ' (';
        $tostring .= $this->species;
        $tostring .= ' - Naissance ';
        $tostring .= $this->year;
        if ($this->isGoingOutside())
            $tostring .= ' - Sort ';
        $tostring .= ')';
        return (string) $tostring;
    }

    public function getString(){
        return (string) ($this->getName() . ' (' . $this->getSpecies() . ')') ?: '?';
    }

}


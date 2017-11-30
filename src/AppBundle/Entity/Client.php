<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Animal;
use Doctrine\ORM\Mapping\Indexes;
use DateTime;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Table(indexes={@ORM\Index(name="last_consultation_idx", columns={"last_consultation"}), @ORM\Index(name="associated_username_idx", columns={"associated_username"}), @ORM\Index(name="FirstName_idx", columns={"FirstName"}), @ORM\Index(name="LastName_idx", columns={"LastName"}), @ORM\Index(name="Phone_idx", columns={"Phone"}), @ORM\Index(name="Phone2_idx", columns={"Phone2"}) })
 * @UniqueEntity(fields="eMail", message="Email already taken")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
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
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=64)
     */
    private $associatedUsername;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Animal", mappedBy="client", cascade={"all"},orphanRemoval=true)
     * @Assert\Valid()
     */
    private $animals;

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
     * @var \DateTime $lastConsultation
     *
     * @ORM\Column(name="last_consultation", type="datetime")
     */
    private $lastConsultation;


    /**
     * @var string
     *
     * @ORM\Column(name="FirstName", type="string", length=64)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="LastName", type="string", length=64)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="CP", type="string", length=8, nullable=true)
     */
    private $cP;

    /**
     * @var string
     *
     * @ORM\Column(name="City", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=32, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone2", type="string", length=32, nullable=true)
     */
    private $phone2;

    /**
     * @var string
     *
     * @ORM\Column(name="NationalNumber", type="string", length=255, nullable=true)
     */
    private $nationalNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="EMail", type="string", length=255, unique=true, nullable=true)
     * @Assert\Email()
     */
    private $eMail;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_preferences", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $contactPreferences;

    /**
     * @var datetime $deletedAt
     *
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;
    /**
     * Set deletedAt
     *
     * @param  \DateTime $deletedAt
     * @return Plan
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }
    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->animals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add animal
     *
     * @param Animal $animal
     *
     * @return Animal
     */
    public function addAnimal(\AppBundle\Entity\Animal $animal)
    {
        $this->animals->add($animal);

        $animal->setClient($this);

        return $this;
    }


    /**
     * Remove animal
     *
     * @param Animal $animal
     */
    public function removeAnimal(\AppBundle\Entity\Animal $animal)
    {
        $this->animals->removeElement($animal);
    }

    public function setPhotosConsultation(Array $animals){
        $this->animals = $animals;

    }

    public function getConsultationWithDebt(){


    }

    /**
     * Get animal
     *
     * @return \Doctrine\Common\Collections\ArrayCollection()
     */
    public function getAnimal()
    {
        return $this->animals;
    }

    /**
     * Get animals
     *
     * @return \Doctrine\Common\Collections\ArrayCollection()
     */
    public function getAnimals()
    {
        return $this->animals;
    }

    /**
     * Get animals
     *
     * @return \Doctrine\Common\Collections\ArrayCollection()
     */
    public function getAnimalsAlive()
    {
        $animalsAlive = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($this->animals as $animal) {
            if($animal->getIsAlive()){
                $animalsAlive->add($animal);
            }
        }
            return $animalsAlive;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAssociatedUsername()
    {
        return $this->associatedUsername;
    }

    public function setAssociatedUsername($associatedUsername)
    {
        $this->associatedUsername = $associatedUsername;
        return $this;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Client
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Client
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Client
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
     * Set cP
     *
     * @param string $cP
     *
     * @return Client
     */
    public function setCP($cP)
    {
        $this->cP = $cP;

        return $this;
    }

    /**
     * Get cP
     *
     * @return string
     */
    public function getCP()
    {
        return $this->cP;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Client
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone2
     *
     * @param string $phone2
     *
     * @return Client
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * Get phone2
     *
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Set nationalNumber
     *
     * @param string $nationalNumber
     *
     * @return Client
     */
    public function setNationalNumber($nationalNumber)
    {
        $this->nationalNumber = $nationalNumber;

        return $this;
    }

    /**
     * Get nationalNumber
     *
     * @return string
     */
    public function getNationalNumber()
    {
        return $this->nationalNumber;
    }

    /**
     * Set eMail
     *
     * @param string $eMail
     *
     * @return Client
     */
    public function setEMail($eMail)
    {
        $this->eMail = $eMail;

        return $this;
    }

    /**
     * Get eMail
     *
     * @return string
     */
    public function getEMail()
    {
        return $this->eMail;
    }

    /**
     * Set contactPreferences
     *
     * @param string $contactPreferences
     *
     * @return Client
     */
    public function setContactPreferences($contactPreferences)
    {
        $this->contactPreferences = $contactPreferences;

        return $this;
    }

    /**
     * Get contactPreferences
     *
     * @return string
     */
    public function getContactPreferences()
    {
        return $this->contactPreferences;
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


    /**
     * Set lastConsultation
     *
     * @param \DateTime $lastConsultation
     *
     * @return Client
     */
    public function setLastConsultation($lastConsultation)
    {
        $this->lastConsultation = $lastConsultation;

        return $this;
    }

    /**
     * Set lastConsultation to now
     *
     * @return Client
     */
    public function setLastConsultationToNow()
    {
        $lastConsultation = new DateTime();
        $this->lastConsultation = $lastConsultation;

        return $this;
    }

    /**
     * Get lastConsultation
     *
     * @return \DateTime
     */
    public function getLastConsultation()
    {
        return $this->lastConsultation;
    }

    /**
     * Set animal
     *
     * @param Array $animals
     * @return Client
     */
    public function setAnimals(Array $animals){
        $this->animals= $animals;
        return $this;
    }

    /**
     * Set animal
     *
     * @param Array $animals
     * @return Client
     */
    public function setAnimal(Array $animals){
        $this->animals= $animals;
        return $this;
    }

    /**
     * Get PhotosConsultation
     *
     * @return \Doctrine\Common\Collections\ArrayCollection()
     */
    public function getPhotosConsultation()
    {
        return $this->photosConsultation;
    }


    public function __toString(){
        return (string)'' . $this->getFirstName() . ' ' . $this->getLastName();
    }
}

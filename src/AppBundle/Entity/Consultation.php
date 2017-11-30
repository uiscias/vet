<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Client;

/**
 * Consultation
 *
 * @ORM\Table(name="consultation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConsultationRepository")
 */
class Consultation
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
     * Many Consultations for One Client.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * Many Consultations for One Animal.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animal")
     * @ORM\JoinColumn(name="animal_id", referencedColumnName="id")
     */
    private $animal;

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
     * @var bool
     *
     * @ORM\Column(name="HasDebts", type="boolean")
     */
    private $hasDebts;

    /**
     * @var float
     *
     * @ORM\Column(name="DebtValueForThisConsultation", type="float", nullable=true)
     */
    private $debtValueForThisConsultation;

    /**
     * @var string
     *
     * @ORM\Column(name="Notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var string
     *
     * @ORM\Column(name="Titre", type="string", length=255, nullable=true)
     */
    private $titre;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PhotosConsultation", mappedBy="consultation", cascade={"all"},orphanRemoval=true)
     * @Assert\Valid()
     */
    private $photosConsultation;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Reminder", mappedBy="consultation", cascade={"all"},orphanRemoval=true)
     * @Assert\Valid()
     */
    private $reminders;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photosConsultation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reminders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reminder
     *
     * @param Reminder $reminder
     *
     * @return Reminder
     */
    public function addReminder(\AppBundle\Entity\Reminder $reminder)
    {
        $this->reminders->add($reminder);
        $reminder->setConsultation($this);

        // $this->attachments[] = $attachment;

        return $this;
    }


    /**
     * Remove reminder
     *
     * @param Reminder $reminder
     */
    public function removeReminder(\AppBundle\Entity\Reminder $reminder)
    {
        $this->reminders->removeElement($reminder);
    }

    public function setReminders(Array $reminders){
        $this->reminders = $reminders;
    }

    /**
     * Get Reminders
     *
     * @return \Doctrine\Common\Collections\ArrayCollection()
     */
    public function getReminders()
    {
        return $this->reminders;
    }

    /**
     * Add photo
     *
     * @param PhotosConsultation $photoConsultation
     *
     * @return Consultation
     */
    public function addPhotosConsultation(\AppBundle\Entity\PhotosConsultation $photosConsultation)
    {
        $this->photosConsultation->add($photosConsultation);

        $photosConsultation->setConsultation($this);

        // $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Get Animal
     *
     * @return \Animal
     */
    public function getAnimal()
    {
        return $this->animal;
    }

    /**
     * Set Animal
     */
    public function setAnimal($animal)
    {
        $this->animal = $animal;
    }


    /**
     * Remove photo
     *
     * @param PhotosConsultation $photoConsultation
     */
    public function removePhotosConsultation(\AppBundle\Entity\PhotosConsultation $photosConsultation)
    {
        $this->photosConsultation->removeElement($photosConsultation);
    }

    public function setPhotosConsultation(Array $photosConsultation){
        $this->photosConsultation = $photosConsultation;

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


    /**
     * Set dateOfConsultation
     *
     * @param \DateTime $dateOfConsultation
     *
     * @return Consultation
     */
    public function setDateOfConsultation($dateOfConsultation)
    {
        $this->created = $dateOfConsultation;

        return $this;
    }

    /**
     * Get dateOfConsultation
     *
     * @return \DateTime
     */
    public function getDateOfConsultation()
    {
        return $this->created;
    }

    /**
     * Set hasDebts
     *
     * @param boolean $hasDebts
     *
     * @return Consultation
     */
    public function setHasDebts($hasDebts)
    {
        $this->hasDebts = $hasDebts;

        return $this;
    }

    /**
     * Get hasDebts
     *
     * @return bool
     */
    public function getHasDebts()
    {
        return $this->hasDebts;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Consultation
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set debtValueForThisConsultation
     *
     * @param float $debtValueForThisConsultation
     *
     * @return Consultation
     */
    public function setDebtValueForThisConsultation($debtValueForThisConsultation)
    {
        $this->debtValueForThisConsultation = $debtValueForThisConsultation;

        return $this;
    }

    /**
     * Get debtValueForThisConsultation
     *
     * @return float
     */
    public function getDebtValueForThisConsultation()
    {
        return $this->debtValueForThisConsultation;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Consultation
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
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
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Consultation
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    public function __toString(){
        return (string) ($this->getDateOfConsultation()->format('d/m/Y H:i') . ' - ' . $this->getClient()->getFirstName() . ' ' . $this->getClient()->getLastName());
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Consultation
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
     * @return Consultation
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
     * Vaccination Done
     *
     * @return boolean
     */
    public function vaccinationDone(Animal $animal){
        foreach($this->reminders as $reminder){
            if ($animal->getId() == $reminder->getAnimal()->getId() && $reminder->getEnabled()){
                return true;
            }
        }
        return false;
    }

    /**
     * Get vaccination Reminder for animal
     *
     * @return Reminder
     */
    public function getReminder(Animal $animal){
        foreach($this->reminders as $reminder){
            if ($animal->getId() == $reminder->getAnimal()->getId() ){
                return $reminder;
            }
        }
        return false;
    }

}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Client;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Reminder
 *
 * @ORM\Table(name="reminder")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReminderRepository")
 */
class Reminder
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
     * Many Reminders for One Client.
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * Many Reminders for One Consultation (as there is many animals).
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Consultation", inversedBy="reminders")
     * @ORM\JoinColumn(name="consultation_id", referencedColumnName="id")
     */
    private $consultation;

    /**
     * Many Reminders for One Animal.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animal")
     * @ORM\JoinColumn(name="animal_id", referencedColumnName="id")
     */
    private $animal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ReminderDateTime", type="datetime")
     */
    private $reminderDateTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="Sent", type="boolean")
     */
    private $sent;

    /**
     * @var bool
     *
     * @ORM\Column(name="Enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="Media", type="string", length=255)
     */
    private $media;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Note", type="text", nullable=true)
     */
    private $note;


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
     * Set reminderDateTime
     *
     * @param \DateTime $reminderDateTime
     *
     * @return Reminder
     */
    public function setReminderDateTime($reminderDateTime)
    {
        $this->reminderDateTime = $reminderDateTime;

        return $this;
    }

    /**
     * Get reminderDateTime
     *
     * @return \DateTime
     */
    public function getReminderDateTime()
    {
        return $this->reminderDateTime;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Reminder
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set sent
     *
     * @param boolean $sent
     *
     * @return Reminder
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return bool
     */
    public function getSent()
    {
        return $this->sent;
    }


    /**
     * Set media
     *
     * @param string $media
     *
     * @return Reminder
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Reminder
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Reminder
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Reminder
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

    /**
     * Set consultation
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Reminder
     */
    public function setConsultation(\AppBundle\Entity\Consultation $consultation = null)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation
     *
     * @return \AppBundle\Entity\Consultation
     */
    public function getConsultation()
    {
        return $this->consultation;
    }

    /**
     * Set animal
     *
     * @param \AppBundle\Entity\Animal $animal
     *
     * @return Reminder
     */
    public function setAnimal(\AppBundle\Entity\Animal $animal = null)
    {
        $this->animal = $animal;

        return $this;
    }

    /**
     * Get animal
     *
     * @return \AppBundle\Entity\Animal
     */
    public function getAnimal()
    {
        return $this->animal;
    }

    public function __toString(){
        return '(' + $this->getClient() + ') ' + $this->getTitle() + ' ' + $this->getEnabled();
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Reminder
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
     * @return Reminder
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
}

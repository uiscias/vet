<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
        $this->dateOfConsultation = $dateOfConsultation;

        return $this;
    }

    /**
     * Get dateOfConsultation
     *
     * @return \DateTime
     */
    public function getDateOfConsultation()
    {
        return $this->dateOfConsultation;
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
        return (string)'' + $this->getDateOfConsultation() + ' - ' + $this->getClient();
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
}

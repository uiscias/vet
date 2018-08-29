<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PhotosManualConsultation
 *
 * @ORM\Table(name="photos_manual_consultation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhotosManualConsultationRepository")
f */
class PhotosManualConsultation
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
     * Many Photos for One Consultation.
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="photosManualConsultation")
     * @ORM\JoinColumn(name="consultation_id", referencedColumnName="id")
     */
    private $consultation;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @var File
     *
     * @Assert\File(maxSize = "5M", mimeTypes={"image/png", "image/jpeg", "image/pjpeg"} )
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="Titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     */
    private $datafile;

    /**
     * Get dataFile
     *
     * @return String
     */
    public function getDatafile()
    {
        return $this->datafile;
    }

    /**
     * Set datafile
     *
     * @param string $datafile
     *
     * @return PhotosManualConsultation
     */
    public function setDatafile($datafile)
    {
        $this->datafile = $datafile;

        return $this;
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
     * Set titre
     *
     * @param string $titre
     *
     * @return PhotosManualConsultation
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
     * Set description
     *
     * @param string $description
     *
     * @return PhotosManualConsultation
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
     * Set consultation
     *
     * @param \AppBundle\Entity\Consultation $consultation
     *
     * @return PhotosManualConsultation
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

    public function __toString(){
        return (string) $this->getId() . ' ' . $this->getTitre();
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return File
     */
    public function setFile(File $file = null) {
        $this->file = $file;
        if($file){
//            $this->uploadAt = new \DateTime();
        }
        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile() {
        return $this->file;
    }


    /**
     * Set link
     *
     * @param string $link
     *
     * @return PhotosManualConsultation
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

}

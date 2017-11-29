<?php
namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class CsvImport
{
    /**
     *
     * @Assert\NotBlank(message="Veuillez joindre un fichier Excel CSV !!!.")
     * @Assert\File(
     *        maxSize = "4M",
     *        maxSizeMessage = "Le fichier à joindre est trop volumineux !!!."
     * )
     */
    private $file;


    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
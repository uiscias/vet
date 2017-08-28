<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
/**
* @SecurityAssert\UserPassword(
*     message = "Mauvaise valeur pour votre mot de passe actuel"
* )
*/
protected $oldPassword;

/**
* @Assert\Length(
*     min = 6,
*     minMessage = "Le mot de passe doit contenir au moins 6 caractères."
* )
*/
protected $newPassword;

public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

}
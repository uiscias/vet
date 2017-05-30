<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSettings
 *
 * @ORM\Table(name="user_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserSettingsRepository")
 */
class UserSettings
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
     * @var int
     *
     * @ORM\Column(name="ReminderInterval", type="integer", nullable=true)
     */
    private $reminderInterval;




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
     * Set reminderInterval
     *
     * @param integer $reminderInterval
     *
     * @return UserSettings
     */
    public function setReminderInterval($reminderInterval)
    {
        $this->reminderInterval = $reminderInterval;

        return $this;
    }

    /**
     * Get reminderInterval
     *
     * @return int
     */
    public function getReminderInterval()
    {
        return $this->reminderInterval;
    }
}


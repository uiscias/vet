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
     * @ORM\Column(name="ReminderInterval", type="integer", nullable=false)
     */
    private $reminderInterval;

    /**
     * @var string
     *
     * @ORM\Column(name="reminderMessage", type="string", length=255, nullable=false)
     */
    private $reminderMessage;


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
     * Set reminderMessage
     *
     * @param String $reminderMessage
     *
     * @return UserSettings
     */
    public function setReminderMessage($reminderMessage)
    {
        $this->reminderMessage = $reminderMessage;

        return $this;
    }

    /**
     * Get reminderMessage
     *
     * @return String
     */
    public function getReminderMessage()
    {
        return $this->reminderMessage;
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


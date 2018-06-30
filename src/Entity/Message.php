<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * The content message
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="message")
     **/
    private $room_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message")
     **/
    private $sender_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message")
     **/
    private $recipient_id;

    /**
     * @ORM\Column(type="datetime")
     **/
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     **/
    private $updated_at;
}

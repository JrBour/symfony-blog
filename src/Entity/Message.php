<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;


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
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message")
     **/
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message")
     **/
    private $recipient;

    /**
     * @ORM\Column(type="datetime")
     **/
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     **/
    private $updated_at;

    /**
     * @return mixed
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the message content
     *
     * @return mixed
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Get the message recipient
     *
     * @return mixed
     */
    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    /**
     * Set the message recipient
     *
     * @return mixed
     */
    public function setRecipient(User $user): void
    {
        $this->recipient = $user;
    }


    /**
     * Get the room message
     *
     * @return mixed
     */
    public function getRoom(): ?Room
    {
        return $this->room;
    }

    /**
     * Set the message room
     *
     * @param       Room      $room     The room object to set
     */
    public function setRoom(Room $room): void
    {
        $this->room = $room;
    }

    /**
     * Get the message sender
     *
     * @return mixed
     */
    public function getSender(): ?User
    {
        return $this->sender;
    }

    /**
     * Set the message sender
     *
     * @return mixed
     */
    public function setSender(User $user): void
    {
        $this->sender = $user;
    }

    /**
     * Get the date where the question was created
     *
     * @return Datetime
     **/
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    /**
     * Set the date where the questions was created
     *
     * @param Datetime       $createdAt      The datetime where the content have been create
     **/
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get the update date
     *
     * @return Datetime
     **/
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    /**
     * Set the update date
     *
     * @param Datetime   $updatedAt  The datime where the content have been update
     **/
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updated_at = $updatedAt;
    }
}

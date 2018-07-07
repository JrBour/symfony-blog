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
    public function getRecipientId(): ?User
    {
        return $this->recipient_id;
    }

    /**
     * Set the message recipient
     *
     * @return mixed
     */
    public function setRecipientId(User $user): void
    {
        $this->recipient_id = $user;
    }


    /**
     * Get the room message
     *
     * @return mixed
     */
    public function getRoomId(): ?Room
    {
        return $this->room_id;
    }

    /**
     * Set the message room
     *
     * @param       Room      $room     The room object to set
     */
    public function setRoomId(Room $room): void
    {
        $this->room_id = $room;
    }

    /**
     * Get the message sender
     *
     * @return mixed
     */
    public function getSenderId(): ?User
    {
        return $this->sender_id;
    }

    /**
     * Set the message sender
     *
     * @return mixed
     */
    public function setSenderId(User $user): void
    {
        $this->sender_id = $user;
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

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var String
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var String
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * Get the room id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the room name
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the room name
     * @param   string    $name     The room name
     * @return Room
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the room picture
     * @return String
     */
    public function getPicture(): String
    {
        return $this->picture;
    }

    /**
     * Set the room picture
     * @param String $picture
     * @return Void
     */
    public function setPicture(String $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * Get the date when the room created
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * Set the date when the room created
     * @param DateTime $created_at
     * @return Void
     */
    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * Get the date when the room updated
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }

    /**
     * Set the date when the room updated
     * @param DateTime $updated_at
     */
    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}

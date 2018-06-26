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
     * @ORM\Column(type="date")
     */
    private $created_at;

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
}

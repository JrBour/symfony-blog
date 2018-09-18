<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png"})
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
     * User id following
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="room")
     */
    private $user;

    /**
     * Get the room id
     * @return mixed
     */
    public function getId(): ?Int
    {
        return $this->id;
    }

    /**
     * Get the room title
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the room title
     * @param   string    $title    The room title
     * @return Room
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the room picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the room picture
     * @param String $picture
     * @return Void
     */
    public function setPicture($picture)
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

    /**
     * Set the user bind to room
     * @param User  $user   The user object
     * @return Room
     */
    public function setUser(User $user): self
    {
        $this->user[] = $user;
        return $this;
    }

    /**
     * Set the user bind to room
     * @return Collection
     */
    public function getUser(): Collection
    {
        return $this->user;
    }
}

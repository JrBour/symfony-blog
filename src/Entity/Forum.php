<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumRepository")
 */
class Forum
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=200)
    * @Assert\NotBlank(message="Un titre est requis")
    **/
    private $title;

    /**
    * @ORM\Column(type="string", nullable=true)
    * @Assert\File(mimeTypes={ "image/jpeg","image/png" })
    **/
    private $picture;

    /**
    * @ORM\Column(type="text")
    **/
    private $content;

    /**
    * @ORM\Column(type="datetime")
    **/
    private $created_at;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    **/
    private $updated_at;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forum")
    * @ORM\JoinColumn(nullable=true)
    **/
    private $author;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="forum")
    **/
    private $answer;

    public function __construct()
    {
      $this->answer = new ArrayCollection();
    }

    /**
    * Return the current id
    *
    * @return Int | $id | The current id of forum
    **/
    public function getId(): ?int
    {
      return $this->id;
    }

    /**
    * Set the forum's id
    *
    * @param Int | $id | Id to add
    **/
    public function setId(int $id): void
    {
      $this->id = $id;
    }

    /**
    * Return the title
    *
    * @return String
    **/
    public function getTitle(): ?string
    {
      return $this->title;
    }

    /**
    * Set the title
    *
    * @param string     $title      Title to add
    **/
    public function setTitle(string $title): void
    {
      $this->title = $title;
    }

    /**
    * Get the content
    *
    * @return string
    **/
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
    * Set the content
    *
    * @var String
    *
    * @return String
    **/
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
    * Get the picture
    *
    * @return String $this Return the url of the picture
    **/
    public function getPicture()
    {
      return $this->picture;
    }

    /**
    * Set the url of the picture
    *
    * @var String $url The url of the picture
    *
    * @return String $this Return the url of the new picture
    **/
    public function setPicture($picture): void
    {
      $this->picture = $picture;
    }

    /**
    * Return the author of the post
    *
    * @return User  Object's user
    **/
    public function getAuthor(): ?User
    {
      return $this->author;
    }

    /**
    * Return the author of the post
    *
    * @var Object | $user | Object's user
    **/
    public function setAuthor(User $user): void
    {
      $this->author = $user;
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
    *
    * @return Datetime
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

    /**
    * Return the forum answers
    *
    * @return Collection|Answer[]
    */
    public function getAnswer(): ?Collection
    {
      return $this->answer;
    }
}

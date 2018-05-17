<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\User;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * The answer id
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The answer content
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    */
    private $content;

    /**
     * The answer picture path
    * @ORM\Column(type="string", nullable=true)
    * @Assert\File(mimeTypes={ "image/jpeg","image/png" })
    **/
    private $picture;

    /**
     * The datetime when the answer have been create
    * @ORM\Column(type="datetime")
    * @Assert\DateTime()
    */
    private $created_at;

    /**
     * The datetime when the answer have been updated
    * @ORM\Column(type="datetime", nullable=true)
    * @Assert\DateTime()
    */
    private $updated_at;

    /**
    * The answer author
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="answer")
    * @ORM\JoinColumn(nullable=true)
    */
    private $author;

    /**
     * The forum bin with the answer
    * @ORM\ManyToOne(targetEntity="App\Entity\Forum", inversedBy="answer")
    * @ORM\JoinColumn(nullable=true)
    */
    private $forum;

    /**
    * Get the answer id
    *
    * @return Int $id The answer id
    */
    public function getId(): ?Int
    {
        return $this->id;
    }

    /**
    * Set the answer id
    *
    * @var Int $id The answer id
    *
    * @return self
    */
    public function setId(int $id): ?Int
    {
      $this->id = $id;
      return $this;
    }

    /**
    * Get the answer content
    *
    * @return String $content The answer content
    */
    public function getContent(): ?String
    {
      return $this->content;
    }

    /**
    * Set the answer content
    *
    * @var String $content The new answer content
    *
    * @return self
    */
    public function setContent(string $content): self
    {
      $this->content = $content;
      return $this;
    }

    /**
    * Return the answer picture
    *
    * @return String $picture The answer picture
    */
    public function getPicture(): ?String
    {
      return $this->picture;
    }

    /**
    * Set the answer picture
    *
    * @var String $picture The answer picture
    *
    * @return self
    */
    public function setPicture(string $picture): ?String
    {
      $this->picture = $picture;
      return $this;
    }

    /**
    * Return the create date
    *
    * @return Datetime $created_at The create date
    */
    public function getCreatedAt(): \DateTime
    {
      return $this->created_at;
    }

    /**
    * Set the create date
    *
    * @var DateTime $date The create date
    *
    * @return self
    */
    public function setCreatedAt(Datetime $date): \DateTime
    {
      $this->created_at = $date;
      return $this->created_at;
    }

    /**
    * Get the update date
    *
    * @return DateTime $updated_at The update date
    */
    public function getUpdatedAt(): ?\DateTime
    {
      return $this->updated_at;
    }

    /**
    * Set the update date
    *
    * @var DateTime $date The update date
    *
    * @return self
    */
    public function setUpdatedAt(DateTime $date): self
    {
      $this->updated_at = $date;
      return $this;
    }

    /**
    * Get the answer author
    *
    * @return Object The answer author
    */
    public function getAuthor()
    {
      return $this->author;
    }

    /**
    * Set the answer author
    *
    * @var Object $author The new answer author
    *
    * @return self
    */
    public function setAuthor(User $author): User
    {
      $this->author = $author;
      return $this->author;
    }

    /**
    * Return the answer forum
    *
    * @return Object $forum The answer forum
    */
    public function getForum()
    {
      return $this->forum;
    }

    /**
    * Set the answer forum
    *
    * @var Object $forum The new answer forum
    *
    * @return self
    */
    public function setForum(Forum $forum): Forum
    {
      $this->forum = $forum;
      return $this->forum;
    }
}

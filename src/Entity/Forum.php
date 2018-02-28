<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Forum;
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
    *
    * @Assert\NotBlank(message="Un titre est requis")
    **/
    private $title;

    /**
    * @ORM\Column(type="string", nullable=true)
    *
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
    * Return the current id
    *
    * @return Int | $id | The current id of forum
    **/
    public function getId()
    {
      return $this->id;
    }

    /**
    * Set the forum's id
    *
    * @var Int | $id | Id to add
    *
    * @return Object | $this | Return the object
    **/
    public function setId(int $id)
    {
      $this->id = $id;
      return $this;
    }

    /**
    * Return the title
    *
    * @return String
    **/
    public function getTitle()
    {
      return $this->title;
    }

    /**
    * Set the title
    *
    * @var String
    *
    * @return String
    **/
    public function setTitle(string $title)
    {
      $this->title = $title;
      return $this;
    }

    /**
    * Get the content
    *
    * @return String
    **/
    public function getContent()
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
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
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
    public function setPicture($picture)
    {
      $this->picture = $picture;
      return $this;
    }

    /**
    * Return the author of the post
    *
    * @return User | User | Object's user
    **/
    public function getAuthor()
    {
      return $this->author;
    }

    /**
    * Return the author of the post
    *
    * @var Object | $user | Object's user
    *
    * @return Object | $this | Object's user
    **/
    public function setAuthor(User $user)
    {
      $this->author = $user;
      return $this;
    }

    /**
    * Get the date where the question was created
    *
    * @return Datetime
    **/
    public function getCreatedAt()
    {
      return $this->created_at;
    }

    /**
    * Set the date where the questions was created
    *
    * @var Datetime
    *
    * @return Datetime
    **/
    public function setCreatedAt(DateTime $createdAt)
    {
      $this->created_at = $createdAt;
      return $this;
    }

    /**
    * Get the update date
    *
    * @return Datetime
    **/
    public function getUpdatedAt()
    {
      return $this->updated_at;
    }

    /**
    * Set the update date
    *
    * @var Datetime
    *
    * @return Datetime
    **/
    public function setUpdatedAt(DateTime $updatedAt)
    {
      $this->updated_at = $updatedAt;
      return $this;
    }

}

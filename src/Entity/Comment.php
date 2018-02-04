<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Blog;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentsRepository")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string")
    **/
    private $content;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Blog", inversedBy="comment")
    * @ORM\JoinColumn(nullable=true)
    **/
    private $blog;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comment")
    * @ORM\JoinColumn(nullable=true)
    **/
    private $author;

    /**
    * @ORM\Column(type="datetime")
    **/
    private $date;

    /**
    * Return id
    *
    * @return int
    **/

    public function getId()
    {
      return $this->id;
    }

    /**
    * Set the comment id
    *
    * @var Int
    *
    * @return Int
    **/
    public function setId(int $id)
    {
      $this->id = $id;
      return $this;
    }

    /**
    * Return the content
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
      return $this->content;
    }

    /**
    * Get the date
    *
    * @return DateTime
    **/
    public function getDate()
    {
      return $this->date;
    }

    /**
    * Set the date
    *
    * @var DateTime
    *
    * @return DateTime
    **/
    public function setDate($date)
    {
      $this->date = $date;
      return $this;
    }

    /**
    * Return the blog
    *
    * @return String
    **/
    public function getBlog()
    {
      $this->blog = $blog;
    }

    /**
    * Set the blog
    *
    * @var String
    *
    * @return String
    **/
    public function setBlog(Blog $blog)
    {
      $this->blog = $blog;
      return $this;
    }

    /**
    * Get the author
    *
    * @return String
    **/
    public function getAuthor()
    {
      return $this->author;
    }

    /**
    * Set the author
    *
    * @var String
    *
    * @return String
    **/
    public function setAuthor(User $author)
    {
      $this->author = $author;

      return $this->author;
    }
}

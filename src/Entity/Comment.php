<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\NotBlank(message="Un commentaire est requis")
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
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     **/
    private $updated_at;

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
     * @return self
     **/
    public function setId(int $id): self
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
     * @return self
     **/
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
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
     * @param Datetime       $createdAt      The datetime where the content have been create
     *
     * @return Datetime
     **/
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->created_at = $createdAt;
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
     * @param Datetime   $updatedAt  The datime where the content have been update
     **/
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updated_at = $updatedAt;
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

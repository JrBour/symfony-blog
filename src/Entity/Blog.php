<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogRepository")
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length = 100)
     * @Assert\NotBlank(message="Un titre est requis")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Une description est requise")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     **/
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     **/
    private $updated_at;

    /**
     * @ORM\Column(type="string")
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png"})
     **/
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="blog")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="blog")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="blog")
     **/
    private $comment;

    /**
     * Blog constructor.
     */
    public function __construct()
    {
        $this->comment = new ArrayCollection();
    }

    /**
     * Get the blog id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the blog id
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the blog title
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the blog title
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * get the blog description
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the blog description
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
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
     * Get the blog picture
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the blog picture
     * @param $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /** Get the blog category
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the blog category
     * @param \App\Entity\Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get the blog author
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the blog author
     * @param \App\Entity\User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * Get the blog comment in an array
     * @return Collection|Comment[]
     **/
    public function getComment()
    {
        return $this->comment;
    }
}

<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\User;
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
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
    * @ORM\Column(type="datetime")
    **/
    private $date;

    /**
    * @ORM\Column(type="string")
    *
    * @Assert\NotBlank(message="Please, upload a new image")
    * @Assert\File(mimeTypes={ "image/jpeg" })
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

    public function getId()
    {
      return $this->id;
    }

    public function setId(int $id)
    {
      $this->id = $id;
      return $this;
    }

    public function getTitle()
    {
      return $this->title;
    }

    public function setTitle(string $title)
    {
      $this->title = $title;
      return $this;
    }

    public function getDescription()
    {
      return $this->description;
    }

    public function setDescription(string $description)
    {
      $this->description = $description;
      return $this;
    }

    public function getDate()
    {
      return $this->date;
    }

    public function setDate($date)
    {
      $this->date = $date;
      return $this;
    }

    public function getImage()
    {
      return $this->image;
    }

    public function setImage($image)
    {
      $this->image = $image;
    }

    public function getCategory()
    {
      return $this->category;
    }

    public function setCategory(Category $category)
    {
      $this->category = $category;
    }

    public function getAuthor()
    {
      return $this->author;
    }

    public function setAuthor(User $author)
    {
      $this->author = $author;
    }
}

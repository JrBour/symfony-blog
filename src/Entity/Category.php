<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank(message="Un nom est requis")
    **/
    private $name;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Blog", mappedBy="category")
    * @Assert\NotBlank(message="Une description est requise")
    **/
    private $blog;

    /**
    * @ORM\Column(type="string")
    *
    * @Assert\File(mimeTypes={ "image/jpeg", "image/png"})
    **/
    private $image;

    /**
     * @ORM\Column(type="datetime")
     **/
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     **/
    private $updated_at;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="category")
    * @ORM\JoinColumn(nullable=true)
    **/
    private $author;

    public function __construct()
    {
      $this->blog = new ArrayCollection();
    }

    public function getId()
    {
      return $this->id;
    }

    public function setId(int $id)
    {
      $this->id = $id;
      return $this;
    }

    public function getName()
    {
      return $this->name;
    }

    public function setName(string $name)
    {
      $this->name = $name;
      return $this;
    }
    /**
    * Return the author of the category
    *
    * @return String
    **/
    public function getAuthor()
    {
      return $this->author;
    }

    /**
    * Set the author of the category
    *
    * @var String
    *
    * @return String
    **/
    public function setAuthor(User $author)
    {
      $this->author = $author;
    }

    /**
    * Return the path of the image
    *
    * @return String
    **/
    public function getImage(){
      return $this->image;
    }

    /**
    * Set the path of the image
    * @param    string      $image      The picture path
    * @return String
    **/
    public function setImage($image)
    {
      $this->image = $image;
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
     * @return Collection|Blog[]
     */
    public function getBlog()
    {
      return $this->blog;
    }
}

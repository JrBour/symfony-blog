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
    * @Assert\NotBlank(message="Please, upload a new image")
    * @Assert\File(mimeTypes={ "image/jpeg", "image/png"})
    **/
    private $image;

    /**
     * @ORM\Column(type="datetime")
     **/
    private $create;

    /**
     * @ORM\Column(type="datetime")
     **/
    private $update;

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
     * Get the date where the blog was created
     * @return mixed
     */
    public function getCreate()
    {
        return $this->create;
    }

    /** Set the data where the blog was created
     * @param   $create
     * @return $this
     */
    public function setCreate($create): self
    {
        $this->create = $create;
        return $this;
    }

    /**
     * Get the date where the blog was updated
     * @return mixed
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /** Set the data where the blog was updated
     * @param       $create         The update datetime
     * @return $this
     */
    public function setUpdate($update): self
    {
        $this->update= $update;
        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlog()
    {
      return $this->blog;
    }
}

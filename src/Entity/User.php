<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Role;
use App\Entity\Blog;
use App\Entity\Forum;
use App\Entity\Answer;
use App\Entity\Category;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="L'email est déjàp pris")
 * @UniqueEntity(fields="username", message="Le pseudo est déjà pris")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    * @ORM\Column(type="string", length=255, unique=true)
    * @Assert\NotBlank()
    * @Assert\Email()
    */
    private $email;

    /**
    * @ORM\Column(type="string", length=255, unique=true)
    * @Assert\NotBlank()
    */
    private $username;

    /**
    * @Assert\Length(max=4096)
    */
    private $plainPassword;

    /**
    * @ORM\Column(type="string", length=64)
    */
    private $password;

    /**
    * @ORM\Column(type="string")
    *
    * @Assert\File(mimeTypes={ "image/jpeg" })
    **/
    private $image;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Blog", mappedBy="user")
    **/
    private $blog;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="user")
    **/
    private $category;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Forum", mappedBy="user")
    **/
    private $author;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="user")
    **/
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $role;

    public function __construct()
    {
      $this->blog = new ArrayCollection();
      $this->comment = new ArrayCollection();
      $this->category = new ArrayCollection();
      $this->author = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId()
    {
        $this->id = $id;
    }

    /**
    * Return the email of user
    **/
    public function getEmail()
    {
      return $this->email;
    }

    /**
    * Set email of user
    */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
      return $this->username;
    }

    public function setUsername(string $username)
    {
      $this->username = $username;
    }

    public function getPlainPassword()
    {
      return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
      $this->plainPassword = $plainPassword;
    }

    public function getPassword()
    {
      return $this->password;
    }

    public function setPassword($password)
    {
      $this->password = $password;
    }

    public function getSalt()
    {
      return null;
    }

    public function getRoles()
    {
      return array($this->role->getName());
    }

    public function getRole()
    {
      return $this->role;
    }

    public function setRole(Role $role)
    {
      $this->role = $role;
    }

    public function eraseCredentials()
    {
    }

    /**
    * Return the path of the image
    *
    * @return String
    **/
    public function getImage()
    {
      return $this->image;
    }

    public function serialize()
    {
      return serialize(array(
        $this->id,
        $this->username,
        $this->password
      ));
    }

    public function unserialize($serialized)
    {
      list(
        $this->id,
        $this->username,
        $this->password
        ) = unserialize($serialized);
    }

    /**
    * Set the path of the image
    *
    * @var String
    *
    * @return String
    **/
    public function setImage($image)
    {
      $this->image = $image;
    }

    /**
    * Return the user's forum post
    *
    * @return Collection|Forum[]| Array of user's forum post
    **/
    public function getForum()
    {
      return $this->forum;
    }

    /**
    * @return Collection|Comment[]
    **/
    public function getComment()
    {
      return $this->comment;
    }

    /**
    * @return Collection|Blog[]
    **/
    public function getBlog()
    {
      return $this->blog;
    }

    /**
    * Get the user answer
    *
    * @return Collection|Answer[]
    */
    public function getAnswer()
    {
      return $this->answer;
    }

    /**
    * @return Collection|Category[]
    **/
    public function getCategory()
    {
      return $this->category;
    }

}

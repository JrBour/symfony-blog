<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * Role id
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Role name
     * @var string
    * @ORM\Column(type="string")
    */
    private $name;

    /**
     * User role
     * @var User
    * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="role")
    **/
    private $user;

    /**
     * Role constructor.
     */
    public function __construct()
    {
      $this->user = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
      return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
      $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
      return $this->name;
    }

    /**
     * @param string $name
     * @return string
     */
    public function setName(string $name)
    {
      $this->name = $name;
      return $this->name;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser()
    {
      return $this->user;
    }
}

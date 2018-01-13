<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    **/
    private $name;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Blog", mappedBy="category")
    **/
    private $blog;

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
     * @return Collection|Blog[]
     */
    public function getBlog()
    {
      return $this->blog;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Blog;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentsRepository")
 */
class Comments
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
    * @ORM\ManyToOne(targetEntity="App\Entity\Blog", inversedBy="comments")
    * @ORM\JoinColumn(nullable=true)
    **/
    private $blog;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
    * @ORM\JoinColumn(nullable=true)
    **/
    private $author;

}

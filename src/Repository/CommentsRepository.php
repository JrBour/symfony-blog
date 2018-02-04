<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
    * Return comments by post
    *
    * @var Int | Id of the post
    *
    * @return Object
    **/
    public function findByPost(int $id)
    {
        $row = $this->createQueryBuilder('c')
            ->where('c.blog = :id')->setParameter('id', $id)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
        if ($row) {
          return $row;
        }
        return false;
    }
}

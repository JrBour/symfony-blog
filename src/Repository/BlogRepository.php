<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BlogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blog::class);
    }


    public function findByCategory(int $id)
    {
        return $this->createQueryBuilder('b')
            ->where('b.category = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        ;
    }

    public function findByAuthor(int $id)
    {
        return $this->createQueryBuilder('b')
            ->where('b.author = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        ;
    }

}

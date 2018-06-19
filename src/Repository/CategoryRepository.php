<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Find the category by user id
     * @param  int   $id    The user id
     * @return mixed
     */
    public function findByAuthor(int $id)
    {
      return $this->createQueryBuilder('c')
          ->where('c.author = :id')
          ->setParameter('id', $id)
          ->getQuery()
          ->getResult();
    }

    /**
     * Find the three last category insert in database
     * @return mixed
     */
    public function findByThreeLast()
    {
      return $this->createQueryBuilder('c')
          ->setMaxResults(3)
          //->orderBy('c.date', 'DESC')
          ->getQuery()
          ->getResult();
    }
}

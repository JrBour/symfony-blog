<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find user by category
     * @param int $id
     * @return mixed
     */
    public function findByCategory(int $id)
    {
        return $this->createQueryBuilder('b')
            ->where('b.category = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        ;
    }

    /**
     * Find the follower for the current user
     * @param       int     $id      The current user id
     * @return mixed
     */
    public function findFollower(int $id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT f.follower_id FROM followers AS f WHERE user_id = ?'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
}

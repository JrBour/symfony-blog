<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }


    public function findOneByRecipientAndSender (int $recipientId, int $senderId)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.recipient = :idRecipient')
            ->andWhere('m.sender = :idSender')
            ->setParameters(['idRecipient' => $recipientId, 'idSender' => $senderId])
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findMessagesByRoomId(int $id)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.room_id = :id')
            ->setParameter('id', $id)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

}

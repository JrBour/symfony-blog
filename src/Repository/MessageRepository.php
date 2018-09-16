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
    /**
     * MessageRepository constructor.
     * @param   RegistryInterface       $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Check if a message exist between two peoples
     * @param       int         $recipientId        The recipient id
     * @param       int         $senderId           The sender id
     * @return    mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByRecipientAndSender (int $recipientId, int $senderId): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.recipient = :idRecipient')
            ->andWhere('m.sender = :idSender')
            ->setParameters(['idRecipient' => $recipientId, 'idSender' => $senderId])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find message by room id
     * @param       int         $id     The room id
     * @return Message      The message bind to the room id
     */
    public function findMessagesByRoomId(int $id): Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.room = :id')
            ->setParameter('id', $id)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

}

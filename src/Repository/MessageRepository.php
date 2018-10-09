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
     * Find the last message sent by the user
     * @param       int         $id     The user ID
     * @return Message|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLastMessage(int $id): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.sender = :idUser')
            ->orWhere('m.recipient = :idUser')
            ->setParameters(['idUser' => $id])
            ->orderBy('m.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find the last message sent by the user
     * @param       int         $id     The user ID
     * @return Message|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLastMessageByRoom(int $id): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.room = :room')
            ->setParameters(['room' => $id])
            ->orderBy('m.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
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
            ->andWhere('m.sender = :idSender')
            ->andWhere('m.recipient = :idRecipient')
            ->orWhere('m.sender = :idRecipient')
            ->andWhere('m.recipient = :idSender')
            ->setParameters(['idRecipient' => $recipientId, 'idSender' => $senderId])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find message by room id
     * @param       int         $id     The room id
     * @return Message      The message bind to the room id
     */
    public function findMessagesByRoomId(int $id): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.room = :id')
            ->setParameter('id', $id)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

}

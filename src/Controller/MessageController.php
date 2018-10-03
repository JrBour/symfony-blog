<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MessageController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/message/create", name="message_create")
     */
    public function create(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $message = new Message();
            $data = json_decode($request->getContent(), true);
            $em = $this->getDoctrine()->getManager();

            $recipient = $em->getRepository(User::class)->find($data['recipient']);
            $sender = $em->getRepository(User::class)->find($data['sender']);
            $room = $em->getRepository(Room::class)->find($data['room']);

            $message->setContent($data['content']);
            $message->setRecipient($recipient);
            $message->setSender($sender);
            $message->setCreatedAt(new \DateTime());
            $message->setRoom($room);

            // Add in the queue
            $em->persist($message);
            // Told  the database that he had to create\update\delete this information
            $em->flush();

            return $this->json(['success' => 'Le message a bien été crée !'], 201);
        }

        return $this->json(['error' => 'Veuillez effectuez une requête ajax !'], 403);
    }
}

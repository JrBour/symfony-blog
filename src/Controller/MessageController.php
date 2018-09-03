<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Message;

class MessageController extends Controller
{
    /**
     * @Route("/message", name="message")
     */
    public function index(Request $request)
    {
        return $this->render('message/index.html.twig', ['controller_name' => 'MessageController']);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request): Response
    {
        if($request->isXmlHttpRequest()) {
            $room = new Room();
            $data = $request->attributes->all();
            $em = $this->getDoctrine()->getManager();

            $room->setTitle($data['name']);
            $room->setPicture($data['picture']);
            $room->setCreatedAt(new \DateTime());

            $em->persist($room);
            $em->flush();
            $data['success'] = "The room have been created with the name" . $room['name'];

            return $this->json($data, 201);
        }

        if ($request->isXmlHttpRequest()) {
            $message = new Message();
            $data = $request->attributes->all();
            $em = $this->getDoctrine()->getManager();

            $message->setContent($data['content']);
            $message->setRecipientId($data['recipient']);
            $message->setSenderId($data['sender']);
            $message->setCreatedAt(new \DateTime());
            $message->setRoomId($data['room']);

            $em->persist($message);
            $em->flush();
        }
    }
}

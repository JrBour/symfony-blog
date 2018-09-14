<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Message;
use App\Form\RoomType;
use App\Form\MessageType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/room")
 */
class RoomController extends Controller
{
    /**
     * @Route("/", name="room_index", methods="GET")
     * Return the page which display the all rooms
     * @return      Response        The response in json
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Message::class)->findMessagesByRecipientId($this->getUser()->getId());

        $rooms = [];
        foreach ($messages as $message) {
            $rooms[$message->getRoomId()->getId()] = $message->getRoomId()->getTitle();
        }
        return $this->render('room/index.html.twig', ['rooms' => $rooms]);
    }

    /**
     * @Route("/new", name="room_new", methods="GET|POST")
     * Return a json response which indicate whether there are an error or no
     * @param       Request         $request        The request
     * @return      Response        The json response
     */
    public function new(Request $request): Response
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

        return $this->json(['error' => 'Une erreur est survenue'], 400);
    }

    /**
     * @Route("/{id}", name="room_show")
     * Return the page which retrieve one room by the room id
     * @param       Room        $room       The room object
     * @param       Request     $request    The request
     * @return      Response        The twig template
     */
    public function show(Request $request, Room $room, int $id): Response
    {
        $message = new Message();
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Message::class)->findMessagesByRoomId($id);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $data = $request->attributes->all();

            $roomToSet = $em->getRepository(Room::class)->find($room->getId());
            $recipient = $em->getRepository(User::class)->find($data['recipient']);
            $sender = $em->getRepository(User::class)->find($data['sender']);

            $message->setContent($data['content']);
            $message->setRecipientId($recipient);
            $message->setSenderId($sender);
            $message->setCreatedAt(new \DateTime());
            $message->setRoomId($roomToSet);

            // Add in the queue
            $em->persist($message);
            // Told  the database that he had to create\update\delete this information
            $em->flush();

            return $this->json(['success' => 'Le message a bien été créée !'], 201);
        }

        return $this->render('room/show.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/{id}/edit", name="room_edit", methods="GET|POST")
     * Edit a room information
     * @param       Request         $request        The request
     * @param       Room            $room           The room object
     * @return      Response        The twig template
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('room_edit', ['id' => $room->getId()]);
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="room_delete", methods="DELETE")
     * Delete a room
     * @param           Request         $request        The request
     * @param           Room            $room           The room object
     * @return          Response        The redirection to another route
     */
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($room);
            $em->flush();
        }

        return $this->redirectToRoute('room_index');
    }
}

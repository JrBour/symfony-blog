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
        $followings = $this->getUser()->getFollowingAndFollower();

        foreach ($followings as $following) {
            $message = $em->getRepository(Message::class)->findOneByRecipientAndSender($following->getId(), $this->getUser()->getId());
            if (!is_null($message)) {
                $following->setRoom($message->getRoom());
            }
        }

        return $this->render('room/index.html.twig', ['users' => $followings]);
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
            $data = json_decode($request->getContent(), true);
            $em = $this->getDoctrine()->getManager();
            $friend = $em->getRepository(User::class)->find($data['id']);

            $room->setTitle($data['name']);
            $room->setPicture($data['picture']);
            $room->setCreatedAt(new \DateTime());
            $room->setUser($this->getUser());
            $room->setUser($friend);

            $friend ->setRoom($room);
            $this->getUser()->setRoom($room);

            $em->persist($room);
            $em->persist($friend);
            $em->persist($this->getUser());
            $em->flush();

            $data['id'] = $room->getId();
            $data['success'] = "Un nouveau salon a été crée !";

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
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Message::class)->findMessagesByRoomId($id);
        $message = reset($messages);
        $users = $room->getUser();

        foreach ($users as $user) {
            if ($user->getId() !== $this->getUser()->getId()) {
                $userId = $user->getId();
                break;
            }
        }

        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
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
            'userId' => $userId,
            'messages' => $messages,
            'form' => $form->createView()
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

<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
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
     * @param       RoomRepository      $roomRepository     The repository room for get the method for retrieve rooms
     * @return      Response        The response in json
     */
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', ['rooms' => $roomRepository->findAll()]);
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
     * @Route("/{id}", name="room_show", methods="GET")
     * Return the page which retrieve one room by the room id
     * @param       Room        $room       The room object
     * @return      Response        The twig template
     */
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', ['room' => $room]);
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

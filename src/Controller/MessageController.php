<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MessageController extends Controller
{
    /**
     * @param       Request         $request
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
            $room = $em->getRepository(Room::class)->find($data['room']);

            if ($data['image']) {
                //$data['image'] = str_replace(' ','+',$data['image']);
                //$data['image'] = base64_decode($data['image']);

                $normalizer = new DataUriNormalizer();
                $file = $normalizer->denormalize($data['image'], 'Symfony\Component\HttpFoundation\File\File');

                if ($file) {
                    $fileName = md5(uniqid()) . '.jpg';
                    $file->move(
                        $this->getParameter('images'),
                        $fileName
                    );
                    $content = "/images/posts/" . $fileName;
                }
            } else {
                $content = $data['content'];
            }


            $message->setContent($content);
            $message->setRecipient($recipient);
            $message->setSender($this->getUser());
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

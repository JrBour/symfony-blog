<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ForumType;
use App\Entity\Forum;
use \DateTime;

class ForumController extends Controller
{
    /**
     * @Route("/forum", name="forum")
     */
    public function indexForumAction()
    {
      $topics = $this->getDoctrine()
        ->getRepository(Forum::class)
        ->findAll();
        return $this->render('forum/index.html.twig',
        array(
            'forums' => $topics,
        ));
    }

    /**
    * @Route("/forum/new", name="forum_add")
    **/
    public function addForumAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();

      $forum = new Forum();
      $form = $this->createForm(ForumType::class, $forum);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
        $forum = $form->getData();

        $file = $forum->getPicture();
        $filename = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
          $this->getParameter('images'),
          $filename
        );
        $picture = "/images/posts/" . $filename;

        $forum->setPicture($picture);
        $forum->setTitle($forum->getTitle());
        $forum->setContent($forum->getContent());
        $dateNow = new DateTime(date('Y-m-d H:i:s'));
        $forum->setCreatedAt($dateNow);

        $em->persist($forum);
        $em->flush();

        return $this->redirectToRoute('forum');
      }

      return $this->render('forum/new.html.twig',
        array(
          'form' => $form->createView()
       ));
    }
}

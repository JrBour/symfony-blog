<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ForumType;
use App\Entity\Forum;
use App\Entity\User;
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
        $user = $this->getUser();

        $file = $forum->getPicture();
        if($file){
          $filename = md5(uniqid()) . '.' . $file->guessExtension();
          $file->move(
            $this->getParameter('images'),
            $filename
          );
          $picture = "/images/posts/" . $filename;
          $forum->setPicture($picture);
        }

        $forum->setTitle($forum->getTitle());
        $forum->setContent($forum->getContent());
        $forum->setAuthor($user);
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
    /**
    * @Route("/forum/edit/{id}", name="forum_edit")
    **/
    public function editForumAction(Request $request, int $id)
    {
      $em = $this->getDoctrine()->getManager();
      $currentForum = $em->getRepository(Forum::class)->find($id);

      $forum = new Forum();
      $form = $this->createForm(ForumType::class, $currentForum);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
        $forum = $form->getData();
        $file = $forum->getPicture();
        if($file){
          $filename = md5(uniqid()) . '.' . $file->guessExtension();
          $file->move(
            $this->getParameter('images'),
            $filename
          );
          $picture = "/images/posts/" . $filename;
          $forum->setPicture($picture);
        }
        $forum->setTitle($forum->getTitle());
        $forum->setContent($forum->getContent());
        $dateNow = new DateTime(date('Y-m-d H:i:s'));
        $forum->setUpdatedAt($dateNow);

        $em->persist($forum);
        $em->flush();
        return $this->redirectToRoute('forum');
      }
      return $this->render('forum/new.html.twig',
      array(
        'form' => $form->createView(),
        'forum' => $currentForum
      ));
    }

    /**
    * @Route("/forum/{id}", name="forum_show")
    **/
    public function showForumAction(Request $request, int $id)
    {
      $forum = $this->getDoctrine()->getManager()->getRepository(Forum::class)->find($id);

      return $this->render('forum/show.html.twig',
        array(
          'forum' => $forum
      ));
    }
}

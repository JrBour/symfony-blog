<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ForumType;
use App\Entity\Forum;
use App\Entity\User;
use App\Entity\Answer;
use App\Form\AnswerType;
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
      // RegEx for find an URL
      $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

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

        if(preg_match($reg_exUrl, $forum->getContent(), $url)) {
           $newContent = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $forum->getContent());
           $forum->setContent($newContent);
        }else{
          $forum->setContent($forum->getContent());
        }

        $forum->setTitle($forum->getTitle());
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
    * @Route("/forum/delete/{id}", name="forum_delete")
    **/
    public function deleteForumAction(Request $request, int $id)
    {
      $em = $this->getDoctrine()->getManager();
      $forum = $em->getRepository(Forum::class)->find($id);

      $em->remove($forum);
      $em->flush();
      return $this->redirectToRoute('forum');
    }

    /**
    * @Route("/forum/{id}", name="forum_show")
    **/
    public function showForumAction(Request $request, int $id)
    {
      $em = $this->getDoctrine()->getManager();
      $forum = $em->getRepository(Forum::class)->find($id);
      $answerByForum = $em->getRepository(Answer::class)->findByForum($id);
      $answers = $em->getRepository(Answer::class)->findAll();

      $answer = new Answer();
      $form = $this->createForm(AnswerType::class, $answer);
      $form->handleRequest($request);

      if($request->isXmlHttpRequest()){
        $data = $request->request->all();
        // $file = $request->files;
        
        $data['user'] = $this->getUser();
        $dateNow = new DateTime(date('Y-m-d H:i:s'));

        $answer->setContent($data['content']);
        $answer->setAuthor($data['user']);
        $answer->setForum($forum);
        $answer->setCreatedAt($dateNow);

        // $file = $data['picture'];
        // if($file){
        //   $filename = md5(uniqid()) . '.' . $file->guessExtension();
        //   $file->move(
        //     $this->getParameter('images'),
        //     $filename
        //   );
        //   $picture = "/images/posts/" . $filename;
        //   $answer->setPicture($picture);
        // }

        $response = 'Votre commentaire a bien était ajouté !';

        $em->persist($answer);
        $em->flush();

        return new JsonResponse($response, 200);
      }
      // if($form->isSubmitted() && $form->isValid()){
      //   $answer = $form->getData();
      //   var_dump($answer);
      //   $user = $this->getUser();
      //   $dateNow = new DateTime(date('Y-m-d H:i:s'));

      //   $file = $answer->getPicture();
      //   if($file){
      //     $filename = md5(uniqid()) . '.' . $file->guessExtension();
      //     $file->move(
      //       $this->getParameter('images'),
      //       $filename
      //     );
      //     $picture = "/images/posts/" . $filename;
      //     $answer->setPicture($picture);
      //   }

      //   $answer->setContent($answer->getContent());
      //   $answer->setCreatedAt($dateNow);
      //   $answer->setAuthor($user);
      //   $answer->setForum($forum);

      //   $em->persist($answer);
      //   $em->flush();

      //   return $this->redirectToRoute('forum_show', array('id' => $forum->getId()));
      // }


      return $this->render('forum/show.html.twig',
        array(
          'forum' => $forum,
          'answers' => $answerByForum,
          'form' => $form->createView()
      ));
    }
}

<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;

class CommentController extends Controller
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
      $comments = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(Comment::class)
                        ->findAll();
      return $this->render('Comment/index.html.twig', array(
        'comments' => $comments
      ));
    }
}
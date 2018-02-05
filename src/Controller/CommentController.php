<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
    * @Route("/comment/remove/{id}", name="comment_remove")
    **/
    public function commentRemoveAction(Request $request, int $id)
    {
      if($request->isXmlHttpRequest()){
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->find($data['id']);


        $em->remove($comment);
        $em->flush();

        $data['success'] = "Le commentaire a bien était supprimé !";
        return new JsonResponse($data, 200);
      }
    }

    /**
    * @Route("/comment/edit/{id}", name="comment_edit")
    **/
    public function commentEditAction(Request $request, int $id)
    {
      if($request->isXmlHttpRequest()){
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->find($data['id']);

        $comment->setContent($data['content']);
        $em->flush();
        
        return new JsonResponse($data, 200);
      }
    }
}

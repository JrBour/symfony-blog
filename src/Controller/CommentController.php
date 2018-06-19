<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Comment;

class CommentController extends Controller
{
    /**
     * Display the whole comment in the dashboard for user whose admin role
     * @param CommentsRepository    $commentsRepository        The repository comment for retrieve the whole comment
     * @return  Response        The response send a view in twig
     * @Route("/comment", name="comment")
     */
    public function index(CommentsRepository $commentsRepository): Response
    {
        return $this->render('Comment/index.html.twig',['comments' => $commentsRepository->findAll() ]);
    }

    /**
     * Edit a comment by ajax request
     * @param Request       $request        The request send by the view
     * @param int           $id             The comment id
     * @return JsonResponse        A json response is send to the view with the status code
     * @Route("/comment/edit/{id}", name="comment_edit")
     */
    public function edit(Request $request, int $id)
    {
        if($request->isXmlHttpRequest()){
            $data = $request->request->all();
            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository(Comment::class)->find($data['id']);
            if (is_null($comment)) {
                $data['error'] = "The id comment does not exist";

                return new JsonResponse($data, 404);
            }
            $comment->setUpdatedAt(new \DateTime());
            $comment->setContent($data['content']);
            $em->flush();

            return new JsonResponse($data, 200);
        }
    }

    /**
     * Remove a comment by ajax request
     * @param Request       $request        The request send by an ajax request
     * @return JsonResponse     Send a JSON response to the view
     * @Route("/comment/remove/{id}", name="comment_remove")
     */
    public function remove(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $data = $request->request->all();
            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository(Comment::class)->find($data['id']);
            if (is_null($comment)) {
                $data['error'] = "L'id ne correspond à aucune commentaire";

                return new JsonResponse($data, 404);
            }
            $em->remove($comment);
            $em->flush();
            $data['success'] = "Le commentaire a bien était supprimé !";

            return new JsonResponse($data, 200);
        }
    }
}

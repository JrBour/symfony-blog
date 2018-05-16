<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Answer;

class AnswerController extends Controller
{
    /**
     * Return all answers in dashboard for the admin
     * @param AnswerRepository      $answerRepository       Repository of the answer part
     * @return      Response        The response send a view in twig
     * @Route("/answer", name="answer")
     */
    public function index(AnswerRepository $answerRepository): Response
    {
        return $this->render('answer/index.html.twig', ['answers' => $answerRepository->findAll()]);
    }

    /**
     * Remove one answer select by his id
     * @param int       $id         The answer id
     * @param int       $idForum    The forum id
     * @return Response     The response send a view in twig
     * @Route("/answer/delete/{id}/{idForum}", name="answer_remove")
     */
    public function removeAnswerAction(int $id, int $idForum): Response
    {
      $em = $this->getDoctrine()->getManager();
      $answer = $em->getRepository(Answer::class)->find($id);
      $em->remove($answer);
      $em->flush();

      return $this->redirectToRoute('forum_show', array('id' => $idForum));
    }
}

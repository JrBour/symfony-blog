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
     * @Route("/answer", name="answer")
     */
    public function listAnswerAction(AnswerRepository $answerRepository): Response
    {
        return $this->render('answer/index.html.twig', ['answers' => $answerRepository->findAll()]);
    }

    /**
    * @Route("/answer/delete/{id}/{idForum}", name="answer_remove")
    */
    public function removeAnswerAction(int $id, int $idForum)
    {
      $em = $this->getDoctrine()->getManager();
      $answer = $em->getRepository(Answer::class)->find($id);

      $em->remove($answer);
      $em->flush();

      return $this->redirectToRoute('forum_show', array('id' => $idForum));
    }
}

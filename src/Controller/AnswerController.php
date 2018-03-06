<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Answer;

class AnswerController extends Controller
{
    /**
     * @Route("/answer", name="answer")
     */
    public function listAnswerAction()
    {
        $answers = $this->getDoctrine()->getManager()->getRepository(Answer::class)->findAll();

        return $this->render('answer/index.html.twig',
          array(
            'answers' => $answers
        ));
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

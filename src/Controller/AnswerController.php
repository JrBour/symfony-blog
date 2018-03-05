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
}

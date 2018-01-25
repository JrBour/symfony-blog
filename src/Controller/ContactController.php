<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;

class ContactController extends Controller
{
  /**
  * @Route("/contact", name="contact")
  **/
  public function showAction(){

      return $this->render('contact/index.html.twig');
  }
}

<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
      $error = $authUtils->getLastAuthenticationError();
      $lastUsername = $authUtils->getLastUsername();

      return $this->render('login/login.html.twig', array(
        'lastUsername' => $lastUsername,
        'error' => $error
      ));
    }
}

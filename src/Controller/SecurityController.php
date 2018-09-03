<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * Return the login page and send if the request contains or not the last username
     * @param AuthenticationUtils   $authUtils     Extracts security error from request
     * @return Response     The response send a view in twig with the last username if he exist
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authUtils): Response
    {

        return $this->render('login/login.html.twig', ['error' => $authUtils->getLastUsername()]);
    }
}

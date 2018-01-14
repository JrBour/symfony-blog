<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
  /**
  * @Route("/", name="home")
  **/
  public function home(UserInterface $user)
  {
    return $this->render('home.html.twig');
  }

  /**
  * @Route("/profil", name="profil")
  */
  public function profile()
  {
    return $this->render('login/profile.html.twig');
  }

  /**
  * @Route("/admin")
  */
  public function admin()
  {
     return new Response('<html><body>Admin page!</body></html>');
  }

}



 ?>

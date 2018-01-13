<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
  /**
  * @Route("/", name="home")
  **/
  public function home ()
  {
    return $this->render('home.html.twig');
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

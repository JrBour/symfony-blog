<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Blog;

class DefaultController extends Controller
{
  /**
  * @Route("/", name="home")
  **/
  public function home(Request $request)
  {
    $posts = $this->getDoctrine()
        ->getRepository(Blog::class)
        ->findByLastThree();

    return $this->render('home.html.twig', array(
      'posts' => $posts
    ));
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

  /**
  * @Route("/profil/{id}", name="profil_user")
  */
  public function profileUserAction(int $id)
  {
    $user = $this->getDoctrine()
          ->getRepository(User::class)
          ->find($id);
    $posts = $this->getDoctrine()
            ->getRepository(Blog::class)
            ->findByAuthor($id);
    $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findByAuthor($id);

    return $this->render('login/profile_user.html.twig', array(
        'user' => $user,
        'posts' => $posts,
        'categories' => $categories
    ));
  }

}



 ?>

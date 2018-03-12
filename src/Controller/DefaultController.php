<?php

namespace App\Controller;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Blog;

class DefaultController extends Controller
{
  /**
  * @Route("/", name="home")
  **/
  public function home(Request $request, TranslatorInterface $translator)
  {
    $posts = $this->getDoctrine()
        ->getRepository(Blog::class)
        ->findByThreeLast();
    $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findByThreeLast();

    $test = $translator->trans('Symfony est incroyable');

    $user = $this->getUser();
    if($user){
      $serial = $user->serialize();
    }else {
      $serial = 'Not connect';
    }


    return $this->render('home.html.twig', array(
      'posts' => $posts,
      'categories' => $categories,
      'test' => $test,
      'serial' => $serial
    ));
  }

  /**
  * @Route("/profil", name="profil")
  */
  public function profile()
  {
    $user = $this->getUser();
    $id = $user->getId();
    $posts = $this->getDoctrine()
              ->getRepository(Blog::class)
              ->findByAuthor($id);
    $categories = $this->getDoctrine()
              ->getRepository(Category::class)
              ->findByAuthor($id);

    return $this->render('login/profile.html.twig', array(
      'posts' => $posts,
      'categories' => $categories
    ));
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

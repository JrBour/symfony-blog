<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BlogType;
use App\Entity\Blog;
use App\Entity\User;
use App\Entity\Category;

use \DateTime;

class BlogController extends Controller
{
  /**
  * @Route("/blog/", name="blog")
  **/
  public function showListBlog()
  {
    $posts = $this->getDoctrine()
      ->getRepository(Blog::class)
      ->findAll();

    if (!$posts) {
      throw $this->createNotFoundException(
        'Aucun post n\'est créer pour l\'instant, pensez à en ajouter un !'
      );
    }

    return $this->render('blog/index.html.twig', array(
      'posts' => $posts
    ));
  }

  /**
  * @Route("/blog/add", name="blog_add")
  **/
  public function addPost(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();

    $post = new Blog();

    $form = $this->createForm(BlogType::class, $post, array( 'choices' => $categories ));
    $form->handleRequest($request); // Permet de manipuler la requête

    if ($form->isSubmitted() && $form->isValid()) {
      $post = $form->getData();
      $user = $this->getUser();

      $post->setTitle($post->getTitle());
      $post->setDescription($post->getDescription());
      $date = new DateTime(date("Y-m-d H:i:s"));
      $post->setDate($date);
      $post->setCategory($post->getCategory());
      $post->setAuthor($user);

      $em->persist($post); // Query : INSERT INTO …
      $em->flush(); // Send the query

      return $this->redirectToRoute('blog');
    }

    return $this->render('blog/add.html.twig', array(
      'form' => $form->createView(),
      'categories' => $categories
    ));
  }
  /**
  * @Route("/blog/{id}", name="show_id")
  **/
  public function showPost(int $id)
  {
      $blog = $this->getDoctrine()
        ->getRepository(Blog::class)
        ->find($id);

      if (!$blog) {
        throw $this->createNotFoundException(
          'Aucun produit n\'a était trouvé pour l\'id n° : ' . $id
        );
      }
      return $this->render('blog/show.html.twig', array(
        'blog' => $blog
      ));
  }

  /**
  * @Route("/blog/edit/{id}", name="edit_post")
  **/
  public function editPost(Request $request, int $id)
  {
    $em = $this->getDoctrine()->getManager();
    $post = $em->getRepository(Blog::class)->find($id);

    if (!$post) {
      throw $this->createNotFoundException(
        'Aucun produit n\'a était trouvé pour l\'id n° : ' . $id
      );
    }

    $form = $this->createForm(BlogType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $post = $form->getData();

      $post->setTitle($post->getTitle());
      $post->setDescription($post->getDescription());

      $em->flush();

      return $this->redirectToRoute('blog');
    }

    return $this->render('blog/edit.html.twig', array(
      'form' => $form->createView(),
      'blog' => $post
    ));
  }

  /**
  * @Route("/blog/delete/{id}", name="remove_post")
  **/
  public function removePost(int $id)
  {
    $em = $this->getDoctrine()->getManager();
    $post = $em->getRepository(Blog::class)->find($id);

    if (!$post) {
      throw $this->createNotFoundException(
        'Aucun produit n\'a était trouvé pour l\'id n° : ' . $id
      );
    }
    $em->remove($post);
    $em->flush();

    return $this->redirectToRoute('blog');
  }
}

 ?>

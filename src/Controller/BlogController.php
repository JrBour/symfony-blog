<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BlogType;
use App\Form\CommentType;
use App\Entity\Blog;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Category;

use \DateTime;

class BlogController extends Controller
{
  /**
  * @Route("/blog", name="blog")
  **/
  public function showListBlog()
  {
    $posts = $this->getDoctrine()
      ->getRepository(Blog::class)
      ->findAll();
      
    //Another way to deny access in controller
    //$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

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

    $form = $this->createForm(BlogType::class, $post, array( 'choices' => $categories));
    $form->handleRequest($request); // Permet de manipuler la requête

    if ($form->isSubmitted() && $form->isValid()) {
      $post = $form->getData();
      $user = $this->getUser();

      $file = $post->getImage();
      $fileName = md5(uniqid()) . '.' . $file->guessExtension();
      $file->move(
        $this->getParameter('images'),
        $fileName
      );
      $name = "/images/posts/" . $fileName;


      $post->setImage($name);
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
  public function showPost(Request $request, int $id)
  {
    $em = $this->getDoctrine()->getManager();
    $blog = $this->getDoctrine()
      ->getRepository(Blog::class)
      ->find($id);
    $comments = $this->getDoctrine()
              ->getRepository(Comment::class)
              ->findByPost($id);

    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($request->isXmlHttpRequest()) {
      $data = $request->request->all();
      $data['user'] = $this->getUser();
      $data['id'] = $data['user']->getId();
      $data['username'] = $data['user']->getUsername();
      $data['image'] = $data['user']->getImage();
      $date = new DateTime(date("Y-m-d H:i:s"));

      $comment->setContent($data['content']);
      $comment->setAuthor($data['user']);
      $comment->setBlog($blog);
      $comment->setDate($date);

      $em->persist($comment);
      $em->flush();

      return new JsonResponse($data, 200);
    }

    return $this->render('blog/show.html.twig', array(
      'blog' => $blog,
      'form' => $form->createView(),
      'comments' => $comments
    ));
  }

  /**
  * @Route("/blog/edit/{id}", name="edit_post")
  **/
  public function editPost(Request $request, int $id)
  {
    $em = $this->getDoctrine()->getManager();
    $currentBlog = $this->getDoctrine()
        ->getRepository(Blog::class)
        ->find($id);
    $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();
    $post = $em->getRepository(Blog::class)->find($id);

    $form = $this->createForm(BlogType::class, $post, array( 'choices' => $categories ));
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $post = $form->getData();
      $file = $post->getImage();
      if ($file) {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
          $this->getParameter('images'),
          $fileName
        );
        $name = "/images/posts/" . $fileName;
        $post->setImage($name);
      } else {
        $post->setImage($currentBlog->getImage());
      }

      $post->setCategory($post->getCategory());
      $post->setTitle($post->getTitle());
      $post->setDescription($post->getDescription());

      $em->flush();

      return $this->redirectToRoute('blog');
    }

    return $this->render('blog/add.html.twig', array(
      'title' => 'edit',
      'form' => $form->createView(),
      'blog' => $post
    ));
  }

  /**
  * @Route("/blog/delete/{id}", name="remove_post", methods="DELETE")
  **/
  public function removePost(int $id)
  {
    $em = $this->getDoctrine()->getManager();
    $post = $em->getRepository(Blog::class)->find($id);

    $em->remove($post);
    $em->flush();

    return $this->redirectToRoute('blog');
  }
}

 ?>

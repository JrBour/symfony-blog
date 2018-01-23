<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use App\Entity\Blog;
use App\Form\CategoryType;

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="category")
     */
    public function showCategory()
    {
      $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();

        return $this->render('category/index.html.twig', array(
          'categories' => $categories
        ));
    }

    /**
    * @Route("/category/post/{id}", name="category_post")
    **/
    public function showPostByCategory(int $id)
    {
      $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->find($id);

      $posts = $this->getDoctrine()
        ->getRepository(Blog::class)
        ->findByCategory($id);

        return $this->render('category/post_category.html.twig', array(
            'posts' => $posts,
            'category' => $category
        ));
    }

    /**
    * @Route("/category/add", name="add_category")
    **/
    public function addCategory(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $category = new Category();

      $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $category = $form->getData();
        $file = $category->getImage();

        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
          $this->getParameter('images'),
          $fileName
        );
        $name = "/images/posts/" . $fileName;

        $category->setImage($name);
        $category->setName($category->getName());
        $category->setAuthor($this->getUser());

        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('category');
      }
      return $this->render('category/add.html.twig', array(
          'form' => $form->createView()
      ));
    }

    /**
    * @Route("/category/edit/{id}", name="edit_category")
    **/
    public function editCategory(Request $request, int $id)
    {
      $em = $this->getDoctrine()->getManager();
      $category = $em->getRepository(Category::class)->find($id);

      if (!$category) {
        throw $this->createNotFoundException(
          'Pas de category correspondant avec l\'id n° :' . $id );
      }
      $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $category = $form->getData();
        $file = $category->getImage();

        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
          $this->getParameter('images'),
          $fileName
        );
        $name = "/images/posts/" . $fileName;

        $category->setImage($name);
        $category->setName($category->getName());
        $em->flush();

        return $this->redirectToRoute('category');
      }

      return $this->render('category/add.html.twig', array(
        'form' => $form->createView(),
        'id' => $id
      ));
    }

    /**
    * @Route("/category/remove/{id}", name="remove_category")
    **/
    public function removeCategory(int $id)
    {
      $em = $this->getDoctrine()->getManager();
      $category = $em->getRepository(Category::class)->find($id);

      if (!$category) {
        throw $this->createNotFoundException(
          'Pas de catégorie correspondant à l\'id n° : ' . $id
        );
      }
      $em->remove($category);
      $em->flush();

      return $this->redirectToRoute('category');
    }
}

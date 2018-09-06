<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Form\BlogType;
use App\Form\CommentType;
use App\Entity\Blog;
use App\Entity\Comment;
use App\Entity\Category;
use \DateTime;

class BlogController extends Controller
{

    /**
     * Render the view with the all posts and categories
     * @param BlogRepository        $blog       Find all the posts
     * @param CategoryRepository    $category   Find all the categories
     * @return Response         Render the view
     * @Route("/blog", name="blog")
     */
    public function index(BlogRepository $blog, CategoryRepository $category): Response
    {
        return $this->render('blog/index.html.twig', ['posts' => $blog->findAll(), 'categories' => $category->findAll()]);
    }

    /**
     * @param Request   $request    The request send by the form
     * @return Response
     * @Route("/blog/add", name="blog_post")
     */
    public function post(Request $request): Response
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
            $post->setCreatedAt(new DateTime());
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
     * Return the page for show only one articles
     * @param Request      $request     The request send by the form
     * @param Comment      $comment     The comment object
     * @param int          $id          The article id
     * @return Response     The render of twig
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $this->getDoctrine()->getRepository(Blog::class)->find($id);
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findByPost($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            $data['user'] = $this->getUser();
            $data['id'] = $data['user']->getId();
            $data['username'] = $data['user']->getUsername();
            $data['image'] = $data['user']->getImage();

            $comment->setContent($data['content']);
            $comment->setAuthor($data['user']);
            $comment->setBlog($blog);
            $comment->setCreatedAt(new DateTime());
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
     * Edit an article, redirect if all conditions is checked and valid
     * @param Request       $request    The request send by the form
     * @param int           $id         The article id
     * @return Response     The render in twig
     * @Route("/blog/edit/{id}", name="blog_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $currentPost = $em->getRepository(Blog::class)->find($id);
        $picture = $currentPost->getImage();

        $form = $this->createForm(BlogType::class, $currentPost, [ 'choices' => $categories ]);
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
                $post->setImage($picture);
            }

            $post->setUpdatedAt(new DateTime());
            $post->setCategory($post->getCategory());
            $post->setTitle($post->getTitle());
            $post->setDescription($post->getDescription());
            $em->flush();

            return $this->redirectToRoute('blog');
        }

        return $this->render('blog/add.html.twig', array(
            'title' => 'edit',
            'form' => $form->createView(),
            'blog' => $currentPost
        ));
    }

    /**
     * Remove an article and redirect to index page where all the articles are displays
     * @param int           $id         The article id
     * @return Response     The redirect to the route blog
     * @Route("/blog/delete/{id}", name="blog_remove")
     */
    public function remove(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Blog::class)->find($id);
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('blog');
    }
}

?>

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Blog;

class DefaultController extends Controller
{
    /**
     * Render the homepage view with the three last articles and categories create
     * @param TranslatorInterface   $translator   Translate word in english/french
     * @return Response     The response send a view in twig
     * @Route("/", name="home")
     */
    public function home(TranslatorInterface $translator): Response
    {
        $posts = $this->getDoctrine()->getRepository(Blog::class)->findByThreeLast();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findByThreeLast();

        $translator->setLocale('en');
        $welcome = $translator->trans('Bienvenue %name%', ['%name%' => '<3']);

        $user = $this->getUser();
        $serial =  ($user) ? $user->serialize() : 'Not connect';

        return $this->render('home.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
            'welcome' => $welcome,
            'serial' => $serial
        ]);
    }

    /**
     * Render the view with the profil of the current user
     * @return Response     The response send a view in twig
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        $user = $this->getUser();
        $id = $user->getId();
        $posts = $this->getDoctrine()->getRepository(Blog::class)->findByAuthor($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findByAuthor($id);

        return $this->render('login/profile.html.twig', [
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

    /**
     * Display the profil of an user
     * @param  int     $id     The user id
     * @return Response       The response send a view in twig
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

        return $this->render('login/profile_user.html.twig', [
            'user' => $user,
            'posts' => $posts,
            'categories' => $categories
        ]);
    }
}

?>

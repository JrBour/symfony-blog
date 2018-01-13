<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Controlle\DefaultController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
      return $this->render('login/login.html.twig');
    }

    /**
     * @Route("/user/show", name="user_show")
     */
    public function registerShowAction()
    {
      $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

        if (!$users) {
          throw  $this->createNotFoundException('
            Vous n\'avez actuellement aucun utilisateur de créer, veuillez en créez une pour commencer.
          ');
        }

        return $this->render('login/show_user.html.twig', array(
          'users' => $users
        ));
    }

    /**
    * @Route("/user/remove/{id}", name="remove_user")
    */
    public function registerDeleteAction(Request $request, int $id)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository(User::class)->find($id);

      $em->remove($user);
      $em->flush();

      return $this->redirectToRoute('user_show');
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user =  new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
          $user->setPassword($password);

          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();

          return $this->redirectToRoute('login');
        }
        return $this->render('login/register.html.twig',
            array(
              'form' => $form->createView()
            )
        );
    }
    /**
     * @Route("/logout", name="logout")
   */
  public function logoutAction()
  {
  }
}

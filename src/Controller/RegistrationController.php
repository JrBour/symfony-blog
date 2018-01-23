<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Entity\Role;
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
            Vous n\'avez actuellement aucun utilisateur de créer, veuillez en créez un pour commencer.
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
        $roles = $this->getDoctrine()
            ->getRepository(Role::class)
            ->findAll();

        $form = $this->createForm(UserType::class, $user, array('choices' => $roles));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

          $file = $user->getImage();
          $fileName = md5(uniqid()) . '.' . $file->guessExtension();
          $file->move(
            $this->getParameter('images'),
            $fileName
          );
          $name = "/images/posts/" . $fileName;

          $user->setImage($name);
          $user->setPassword($password);
          $user->setRole($user->getRole());


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
    * @Route("/user/{id}", name="user_edit")
    **/
    public function editUserAction(int $id, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
      $newUser = new User();
      $em = $this->getDoctrine();
      $user = $em->getRepository(User::class)->find($id);
      $roles = $em->getRepository(Role::class)->findAll();

      $form = $this->createForm(UserType::class, $user, array('choices' => $roles));
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $file = $user->getImage();

        if($file) {
          $fileName = md5(uniqid()) . '.' . $file->guessExtension();
          $file->move(
            $this->getParameter('images'),
            $fileName
          );
          $name = "/images/posts/" . $fileName;
          $newUser->setImage($name);
        } else {
          $newUser->setImage($user->getImage());
        }
        
        $newUser->setRole($user->getRole());
        $newUser->setPassword($password);
        $fm = $this->getDoctrine()->getManager();
        $fm->persist($newUser);
        $fm->flush();

        return $this->redirectToRoute('home');
      }
      return $this->render('login/register.html.twig', array(
        'title' => 'edite',
        'form' => $form->createView(),
        'user' => $user
      ));
    }
    /**
     * @Route("/logout", name="logout")
   */
  public function logoutAction()
  {
  }
}

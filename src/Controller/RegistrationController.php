<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Role;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
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
    public function registerShowAction(UserRepository $userRepository)
    {
      $users = $userRepository->findAll();

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
          $user = $form->getData();

          $file = $user->getImage();
          if($file) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
              $this->getParameter('images'),
              $fileName
            );
            $name = "/images/posts/" . $fileName;
            $user->setImage($name);
          } else {
            $user->setImage($user->getImage());
          }
          if($user->getPassword()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
          } else {
            $user->setPassword($user->getPassword());
          }
          $user->setRole($user->getRole());

          $em = $this->getDoctrine()->getManager();
          $em->flush();

          return $this->redirectToRoute('user_show');
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
    public function editUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, int $id)
    {
      $em = $this->getDoctrine()->getManager();
      $title = 'edit';
      $roles = $em->getRepository(Role::class)->findAll();
      $user = $em->getRepository(User::class)->find($id);

      $form = $this->createForm(UserType::class, $user, array('choices' => $roles));
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        
        if ($user->getPlainPassword()) {
          $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
          $user->setPassword($password);
        } else {
          $user->setPassword($user->getPassword());
        }
        $file = $user->getImage();
        if($file) {
          $fileName = md5(uniqid()) . '.' . $file->guessExtension();
          $file->move(
            $this->getParameter('images'),
            $fileName
          );
          $name = "/images/posts/" . $fileName;
          $user->setImage($name);
        } else {
          $user->setImage($user->getImage());
        }
        $user->setRole($user->getRole());


        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('home');
      }

      return $this->render('login/register.html.twig', array(
        'form' => $form->createView(),
        'title' => 'edit'
      ));
    }

  /**
    * @Route("/logout", name="logout")
   */
  public function logoutAction()
  {
  }
}

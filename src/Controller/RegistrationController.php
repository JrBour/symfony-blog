<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Role;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * Return the view page to log in
     * @return Response     Return a view page in twig
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('login/login.html.twig');
    }

    /**
     * Display whole register user on the site
     * @param UserRepository        $userRepository     The repository of user
     * @return Response     Return a view page in twig with the all users
     * @Route("/user/show", name="user_show")
     */
    public function allUserRegister(UserRepository $userRepository)
    {
        return $this->render('login/show_user.html.twig', ['users' => $userRepository]);
    }

    /**
     * Remove an user of the database
     * @param int       $id         The user id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse       Redirect to the route where whole users are displays
     * @Route("/user/remove/{id}", name="user_remove")
     */
    public function removeUser(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_show');
    }

    /**
     * Register a new user with the role user and set a picture profil a picture is send by the form
     * @param Request                           $request         Request sent by the form
     * @param UserPasswordEncoderInterface      $passwordEncoder Encode the plain password
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response Redirect after the validation the form or return a view twig page
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

        return $this->render('login/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit an user by changing his password, picture profil, name…
     * @param Request                       $request            The request sent by the form
     * @param UserPasswordEncoderInterface  $passwordEncoder    Encode the plain password
     * @param int                           $id                 The user id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response Redirect to another page after the validation of the form
     * or return a view in twig with the form
     * @Route("/user/{id}", name="user_edit")
     */
    public function editUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, int $id)
    {
        $em = $this->getDoctrine()->getManager();
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

        return $this->render('login/register.html.twig', [
            'form' => $form->createView(),
            'title' => 'edit'
        ]);
    }

    /**
     * Allow to logout the current user
     * @return void
     * @Route("/logout", name="logout")
     */
    public function logoutAction(): void
    {
    }
}

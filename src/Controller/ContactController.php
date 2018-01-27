<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactType;

class ContactController extends Controller
{
  /**
  * @Route("/contact", name="contact")
  **/
  public function createAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $contacts = $em->getRepository(Contact::class)->findAll();
    $contact = new Contact();

    $form = $this->createForm(ContactType::class, $contact);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $contact = $form->getData();

      $contact->setFirstname($contact->getFirstname());
      $contact->setLastname($contact->getLastname());
      $contact->setMail($contact->getMail());
      $contact->setMessage($contact->getMessage());

      $em->persist($contact);
      $em->flush();

      return $this->render('contact/index.html.twig', array(
          'form' => $form->createView(),
          'success' => 'Le message a bien était envoyé !'
      ));
    }

    return $this->render('contact/index.html.twig', array(
      'form' => $form->createView()
    ));
  }

  /**
  * @Route("/contact/all", name="show_all_contact")
  **/
  public function showAction()
  {
    $contacts = $this->getDoctrine()
                      ->getManager()
                      ->getRepository(Contact::class)
                      ->findAll();
    return $this->render('contact/show_all.html.twig', array(
          'contacts' => $contacts
    ));
  }
  /**
  * @Route("/contact/show/{id}", name="show_contact")
  **/
  public function showMessageAction(int $id)
  {
    $contact = $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Contact::class)
                    ->find($id);
    return $this->render('contact/show.html.twig');
  }
}

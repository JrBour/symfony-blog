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
}

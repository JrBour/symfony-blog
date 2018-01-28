<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    if($request->isXmlHttpRequest()) {
      $data = $request->request->all();
      $contact = $form->getData();

      $contact->setFirstname($data['firstname']);
      $contact->setLastname($data['lastname']);
      $contact->setMail($data['mail']);
      $contact->setMessage($data['message']);

      $em->persist($contact);
      $em->flush();
      $success = ['output' => 'Le formulaire a était envoyé !'];
      return new JsonResponse($success);
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
    return $this->render('contact/show.html.twig', array(
      'contact' => $contact
    ));
  }

  /**
  * @Route("/contact/remove/{id}", name="remove_contact")
  **/
  public function deleteAction(int $id)
  {
    $em = $this->getDoctrine()->getManager();
    $contact = $em->getRepository(Contact::class)->find($id);

    $em->remove($contact);
    $em->flush();

    return $this->redirectToRoute('show_all_contact');
  }
}

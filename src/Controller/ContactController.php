<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Form\ContactType;

class ContactController extends Controller
{
    /**
     * Return whole contact in dashboard page for user whose admin role
     * @param ContactRepository     $contact        The repository for the contact part
     * @return Response         The response send a view in twig template
     * @Route("/contact/all", name="contact_index")
     */
    public function index(ContactRepository $contact)
    {
        return $this->render('contact/show_all.html.twig', ['contacts' => $contact->findAll()]);
    }

    /**
     * Return a specific message in twig template
     * @param Contact       $contact        The contact entity
     * @return Response     The response send a twig template with the contact object
     * @Route("/contact/show/{id}", name="contact_show")
     */
    public function show(Contact $contact)
    {
        return $this->render('contact/show.html.twig', ['contact' => $contact]);
    }

    /**
     * Create a new message from contact page by ajax request
     * @param Request       $request        The request send in ajax from the view
     * @return JsonResponse|Response        Response send in json for answer to ajax request and send a view in twig for the form
     * @Route("/contact", name="contact")
     */
    public function post(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
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

            return new JsonResponse($success, 200);
        }

        return $this->render('contact/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Remove a message by ajax request
     * @param Request       $request        The request send by the view
     * @return JsonResponse     Return a json reponse for success and fail
     * @Route("/contact/delete/{id}", name="contact_remove")
     */
    public function remove(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();
            $contact = $em->getRepository(Contact::class)->find($data['id']);
            if (is_null($contact)) {
                $data['error'] = "The message doesn't exist";

                return new JsonResponse($data, 404);
            }
            $response['success'] = 'Le message a bien était supprimé';
            $em->remove($contact);
            $em->flush();

            return new JsonResponse($response, 200);
        }
    }
}

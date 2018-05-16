<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ForumRepository;
use App\Form\ForumType;
use App\Entity\Forum;
use App\Entity\User;
use App\Entity\Answer;
use App\Form\AnswerType;
use \DateTime;

class ForumController extends Controller
{
    /**
     * Display the all discussion forum
     * @param ForumRepository       $forumRepository    The repository of forum
     * @return Response    The response send a view in twig
     * @Route("/forum", name="forum")
     */
    public function index(ForumRepository $forumRepository)
    {
        return $this->render('forum/index.html.twig', ['forums' => $forumRepository->findAll()]);
    }

    /**
     * Add a new discussion on forum part. If one or many links are in the content, a regexp add an href around this content
     * @param Request         $request      A request send by the form
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response Redirect to another route after the validation of the form
     * @Route("/forum/new", name="forum_add")
     */
    public function post(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);
        // RegEx for find an URL
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

        if($form->isSubmitted() && $form->isValid()){
            $forum = $form->getData();
            $user = $this->getUser();

            $file = $forum->getPicture();
            if($file){
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('images'),
                    $filename
                );
                $picture = "/images/posts/" . $filename;
                $forum->setPicture($picture);
            }

            if(preg_match($reg_exUrl, $forum->getContent(), $url)) {
                $newContent = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $forum->getContent());
                $forum->setContent($newContent);
            }else{
                $forum->setContent($forum->getContent());
            }

            $forum->setTitle($forum->getTitle());
            $forum->setAuthor($user);
            $dateNow = new DateTime(date('Y-m-d H:i:s'));
            $forum->setCreatedAt($dateNow);
            $em->persist($forum);
            $em->flush();

            return $this->redirectToRoute('forum');
        }

        return $this->render('forum/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit the content of the current discussion
     * @param Request       $request        The request send by the form
     * @param int           $id             The forum id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response      The responser redirect or render a new view in twig
     * @Route("/forum/edit/{id}", name="forum_edit")
     */
    public function edit(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $currentForum = $em->getRepository(Forum::class)->find($id);
        $form = $this->createForm(ForumType::class, $currentForum);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $forum = $form->getData();
            $file = $forum->getPicture();
            if($file){
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('images'),
                    $filename
                );
                $picture = "/images/posts/" . $filename;
                $forum->setPicture($picture);
            }
            $forum->setTitle($forum->getTitle());
            $forum->setContent($forum->getContent());
            $dateNow = new DateTime(date('Y-m-d H:i:s'));
            $forum->setUpdatedAt($dateNow);
            $em->persist($forum);
            $em->flush();

            return $this->redirectToRoute('forum');
        }

        return $this->render('forum/new.html.twig', [
                'form' => $form->createView(),
                'forum' => $currentForum
            ]);
    }

    /**
     * Remove a discussion thanks to his identifiant
     * @param int       $id         The forum id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Redirect to forum home page
     * @Route("/forum/delete/{id}", name="forum_delete")
     */
    public function remove(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $forum = $em->getRepository(Forum::class)->find($id);
        $em->remove($forum);
        $em->flush();

        return $this->redirectToRoute('forum');
    }

    /**
     * Show a specific discussion thanks to his id
     * @param Request   $request        The request ajax send for create a comment by an user
     * @param int       $id             The forum id
     * @return JsonResponse|Response     Return a json response for the ajax and a view in twig for show the discussion page
     * @Route("/forum/{id}", name="forum_show")
     */
    public function show(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $forum = $em->getRepository(Forum::class)->find($id);
        $answerByForum = $em->getRepository(Answer::class)->findByForum($id);
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()){
            $data = $request->request->all();
            // $file = $request->files;

            $data['user'] = $this->getUser();
            $dateNow = new DateTime(date('Y-m-d H:i:s'));
            $answer->setContent($data['content']);
            $answer->setAuthor($data['user']);
            $answer->setForum($forum);
            $answer->setCreatedAt($dateNow);

            // $file = $data['picture'];
            // if($file){
            //   $filename = md5(uniqid()) . '.' . $file->guessExtension();
            //   $file->move(
            //     $this->getParameter('images'),
            //     $filename
            //   );
            //   $picture = "/images/posts/" . $filename;
            //   $answer->setPicture($picture);
            // }

            $data['success'] = 'Votre commentaire a bien était ajouté !';
            $em->persist($answer);
            $em->flush();

            return new JsonResponse($data, 200);
        }

        return $this->render('forum/show.html.twig', [
                'forum' => $forum,
                'answers' => $answerByForum,
                'form' => $form->createView()
            ]);
    }
}

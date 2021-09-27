<?php
namespace App\Controller\Admin;



use App\Entity\Play;
use App\Form\PlayType;
use App\Repository\PlayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/play")
 */
class PlayController extends AbstractController
{
    /**
     * @Route("/", name="play_index", methods={"GET"})
     */
    public function index(PlayRepository $playRepository): Response
    {
        return $this->render('play/index.html.twig', [
            'plays' => $playRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="play_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $play = new Play();
        $form = $this->createForm(PlayType::class, $play);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($play);
            $entityManager->flush();

            return $this->redirectToRoute('play_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('play/new.html.twig', [
            'play' => $play,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="play_show", methods={"GET"})
     */
    public function show(Play $play): Response
    {
        return $this->render('play/show.html.twig', [
            'play' => $play,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="play_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Play $play): Response
    {
        $form = $this->createForm(PlayType::class, $play);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('play_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('play/edit.html.twig', [
            'play' => $play,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="play_delete", methods={"POST"})
     */
    public function delete(Request $request, Play $play): Response
    {
        if ($this->isCsrfTokenValid('delete'.$play->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($play);
            $entityManager->flush();
        }

        return $this->redirectToRoute('play_index', [], Response::HTTP_SEE_OTHER);
    }
}

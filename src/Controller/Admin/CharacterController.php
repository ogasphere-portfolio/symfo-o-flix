<?php

namespace App\Controller\Admin;

use App\Entity\Character;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/character")
 */
class CharacterController extends AbstractController
{
    /**
     * @Route("/", name="admin_character_index", methods={"GET"})
     */
    public function index(CharacterRepository $characterRepository): Response
    {
        return $this->render('admin/character/index.html.twig', [
            'characters' => $characterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_character_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($character);
            $entityManager->flush();

            return $this->redirectToRoute('admin_character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/character/new.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_character_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Character $character): Response
    {
        return $this->render('admin/character/show.html.twig', [
            'character' => $character,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_character_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Character $character): Response
    {
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/character/edit.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_character_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Character $character): Response
    {
        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_character_index', [], Response::HTTP_SEE_OTHER);
    }
}

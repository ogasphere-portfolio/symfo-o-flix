<?php

namespace App\Controller\Admin;

use App\Entity\TvShow;
use App\Form\TvShowType;
use App\Repository\TvShowRepository;
use App\Utils\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tvshow")
 * @IsGranted("ROLE_ADMIN")
 */
class TvShowController extends AbstractController
{
    /**
     * @Route("/", name="admin_tv_show_index", methods={"GET"})
     */
    public function index(TvShowRepository $tvShowRepository): Response
    {
        return $this->render('admin/tv_show/index.html.twig', [
            'tv_shows' => $tvShowRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_tv_show_new", methods={"GET","POST"})
     */
    public function new(Request $request,OmdbApi $omdbApi): Response
    {
        $tvShow = new TvShow();
        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
             // on récupère l'url de l'image depuis l'api
             $imageUrlFromApi = $omdbApi->getImageUrlFromApi($tvshow->getTitle());
             // si une image a bien été trouvé, alors l'url est différente de "NOT FOUND" 
             // que l'on a rangé dans une constante
             if ($imageUrlFromApi !== OmdbApi::IMAGE_NOT_FOUND) {
                 $tvShow->setImage($imageUrlFromApi);
             }
            $entityManager->persist($tvShow);
            $entityManager->flush();

            return $this->redirectToRoute('admin_tv_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/tv_show/new.html.twig', [
            'tv_show' => $tvShow,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_tv_show_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(TvShow $tvShow): Response
    {
        return $this->render('admin/tv_show/show.html.twig', [
            'tv_show' => $tvShow,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_tv_show_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, TvShow $tvShow): Response
    {
       
        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_tv_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/tv_show/edit.html.twig', [
            'tv_show' => $tvShow,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_tv_show_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, TvShow $tvShow): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$tvShow->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tvShow);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_tv_show_index', [], Response::HTTP_SEE_OTHER);
    }
}

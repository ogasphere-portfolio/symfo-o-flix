<?php

namespace App\Controller;

use App\Repository\TvShowRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * Les annotations de routes au niveau de la classe servent de préfixe à toutes les routes définies dans celle ci
 * 
 * @Route("/tvshow", name="tvshow")
*/
class TvShowController extends AbstractController
{

     /**
     * @Route("/", name="_browse")
     */
    public function home(TvShowRepository $tvShowRepository): Response
    {
        // récupérer le tvshow dont l'id est fourni (paramConverter ou repository)
        $tvShows = $tvShowRepository->findAll();

        // actuellement pleins de requetes sont effectuées pour récupérées les données au compte goutte

        return $this->render('tv_show/index.html.twig', [
            'tvShows' => $tvShows,
        ]);
    }
   /**
     * @Route("/{id}", name="_read")
     */
    public function read($id, TvShowRepository $tvShowRepository): Response
    {
        // récupérer le tvshow dont l'id est fourni (paramConverter ou repository)
        $tvShow = $tvShowRepository->findOneWithAllInfos($id, true, true);

        // actuellement pleins de requetes sont effectuées pour récupérées les données au compte goutte

        // 
        return $this->render('tv_show/read.html.twig', [
            'tv_show' => $tvShow,
            'id' => $id,
        ]);
    }
}

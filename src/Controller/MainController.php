<?php

namespace App\Controller;

use App\Repository\TvShowRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class MainController extends AbstractController
{
     /**
     * @Route("/", name="home")
     */
    public function home(TvShowRepository $tvShowRepository): Response
    {
        // récupérer le tvshow dont l'id est fourni (paramConverter ou repository)
        $tvShow = $tvShowRepository->findAll();

        // actuellement pleins de requetes sont effectuées pour récupérées les données au compte goutte

        return $this->render('home.html.twig', [
            'tv_show' => $tvShow,
        ]);
    }
}
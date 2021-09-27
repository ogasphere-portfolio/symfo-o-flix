<?php
namespace App\Controller\Api;



use Betaseries\Betaseries;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




/**
 * ApiController
 * * @Route("/api", name="api")
 */
class ApiController extends AbstractController
{

        
     /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $betaseries = new \Betaseries\Betaseries('717fa6d4ae92');
        $client = new \Betaseries\Client($betaseries);
        // Affiche les informations d'une série. (Game of Thrones)
        $show = $client->api('shows')->lists();
        // actuellement pleins de requetes sont effectuées pour récupérées les données au compte goutte
        
        return $this->render('home.html.twig', [
            'tvShows' => $show,
        ]);
    }

   
}
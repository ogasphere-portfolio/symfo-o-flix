<?php
namespace App\Controller\Api;



use App\Utils\OmdbApi;
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
    
     /**
     * Affiche 3 séries au hasard
     * 
     * @Route("/omdbapi", name="homepage")
     */
    public function ombdapi(OmdbApi $omdbApi): Response
    {
        dd($omdbApi->loadImageFromApi('Mr Robot'));

        // Le template n'affichera que 3 éléments
        return $this->render('main/homepage.html.twig', [
        ]);
    }
   
}
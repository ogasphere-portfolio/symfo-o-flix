<?php

namespace App\Controller\Api\V1;

use App\Entity\TvShow;
use App\Repository\TvShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/v1/tvshows", name="api_v1_tvshows_")
 */
class TvshowsController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(TvShowRepository $tvShowRepository): Response
    {
        $allTvShows = $tvShowRepository->findAll();
        // dd($allTvShows);

        return $this->json($allTvShows, Response::HTTP_OK, [], ['groups' => 'api_tvshow_browse']);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function read(int $id, TvShowRepository $tvShowRepository): Response
    {
        $tvShow = $tvShowRepository->find($id);

        if (is_null($tvShow)) {
            $responseArray = [
                'error' => true,
                'userMessage' => 'Ressource non trouvé',
                'internalMessage' => 'Ce tv show n\'existe pas dans la BDD',
            ];

            return $this->json($responseArray, Response::HTTP_NOT_FOUND);

        }

        return $this->json($tvShow, Response::HTTP_OK, [], ['groups' => 'api_tvshow_browse']);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {

        $jsonContent = $request->getContent();
        $tvShow = $serializer->deserialize($jsonContent, TvShow::class, 'json');

        $entityManager->persist($tvShow);
        dd($entityManager);
        $entityManager->flush();
        
        $reponseAsArray = [
            'message' => 'Tvshow créé',
            'id' => $tvShow->getId()
        ];

        return $this->json($reponseAsArray, Response::HTTP_CREATED);
    }

}

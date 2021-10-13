<?php

namespace App\Controller\Api\V1;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/categories", name="api_v1_categories_")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(CategoryRepository $categoryRepository): Response
    {
        $allCategories = $categoryRepository->findAll();

        return $this->json($allCategories, Response::HTTP_OK, [], ['groups' => 'api_category_browse']);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function read(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (is_null($category)) {
            return $this->getNotFoundResponse();
        }

        return $this->json($category, Response::HTTP_OK, [], ['groups' => 'api_category_browse']);
    }

    /**
     * @Route("/{id}", name="edit", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function edit(ValidatorInterface $validator, int $id, CategoryRepository $categoryRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        // on récupère le category  qui est en BDD
        $category = $categoryRepository->find($id);

        if (is_null($category)) {
            return $this->getNotFoundResponse();
        }

        // on récupère le json fournit par le client
        $jsonContent = $request->getContent();

        /*
        Désérialise ces données
        je veux obtenir un objet de la classe Category
        au fait les données sont au format json
        met à jour l'objet $category que je te fournit dans le contexte avec les données
        */
        // mettre à jour le category avec les données fournies (le deserialize s'en occupe)
        $serializer->deserialize($jsonContent, Category::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $category
        ]);

        // validation du category
        $errors = $validator->validate($category);

        // s'il y a eu au moins une erreur
        if(count($errors) > 0)
        {
            $reponseAsArray = [
                'error' => true,
                'message' => $errors,
            ];

            return $this->json($reponseAsArray, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        
        // lancer le flush
        $entityManager->persist($category);
        $entityManager->flush();
        
        $reponseAsArray = [
            'message' => 'Category mis à jour',
            'id' => $category->getId()
        ];

        return $this->json($reponseAsArray, Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(ValidatorInterface $validator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $jsonContent = $request->getContent();
        $category = $serializer->deserialize($jsonContent, Category::class, 'json');

        // validation des données
        $errors = $validator->validate($category);

        // s'il y a eu au moins une erreur
        if(count($errors) > 0)
        {
            $reponseAsArray = [
                'error' => true,
                'message' => $errors,
            ];

            return $this->json($reponseAsArray, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->persist($category);
        $entityManager->flush();
        
        $reponseAsArray = [
            'message' => 'Category créé',
            'id' => $category->getId()
        ];

        return $this->json($reponseAsArray, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        // on récupère le category  qui est en BDD
        $category = $categoryRepository->find($id);

        if (is_null($category)) {
            return $this->getNotFoundResponse();
        }

        
        // lancer le flush
        $entityManager->remove($category);
        $entityManager->flush();
        
        $reponseAsArray = [
            'message' => 'Category supprimé',
            'id' => $id
        ];

        return $this->json($reponseAsArray);
    }
    
    private function getNotFoundResponse() {

        $responseArray = [
            'error' => true,
            'userMessage' => 'Ressource non trouvé',
            'internalMessage' => 'Ce tv show n\'existe pas dans la BDD',
        ];

        return $this->json($responseArray, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}

<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/add", name="add", methods={"GET"})
     */
    public function add(): Response
    {

        // on a créé un formulaire vierge (sans données initiales)
        $categoryForm = $this->createForm(CategoryType::class);

        // on fournit ce formulaire à notre vue
        return $this->render('admin/category/add.html.twig', [
            'category_form' => $categoryForm->createView(),
        ]);
    }
}

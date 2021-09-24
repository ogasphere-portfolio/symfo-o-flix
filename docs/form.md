# Création / utilisation d'un formulaire

La documentation est ici : [Doc symfony](https://symfony.com/doc/current/forms.html)

## Installer le composant

`composer require symfony/form` 

## Créer une classe de formulaire

` bin/console make:form `

Modifier cette classe pour avoir l'affichage souhaité

## Afficher le formulaire

Dans un controleur on va créer un objet de formulaire pour l'afficher

cf Controller\Admin\CategoryController.php::update

```php

    // création du formulaire
    $categoryForm = $this->createForm(CategoryType::class, $category);

    // ici le code de traitement du formulaire voir plus bas

    // affichage du formulaire
    return $this->render('admin/category/add.html.twig', [
        'category_form' => $categoryForm->createView(),
    ]);
```

Dans twig utiliser les fonctions `form_` pour afficher le formulaire cf [la doc](https://symfony.com/doc/current/form/form_customization.html#reference-form-twig-functions)

## Traiter le formulaire

Dans la meme méthode qui affiche le formulaire, ajouter entre la création et l'affichage du formulaire.

```php

    $categoryForm->handleRequest($request);

    if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->flush();

        $this->addFlash('success', "La catégorie `{$category->getName()}` a bien été mise à jour");

        return $this->redirectToRoute('admin_category_browse');
    }
```

## Pour ajouter des contraintes de validation

Cela se passe au niveau de l'entité en ajoutant des contraintes à l'aide d'annotations : cf [la doc sur les contraintes](https://symfony.com/doc/current/validation.html#constraints)

## Pour modifier le comportement d'affichage du formulaire

Cela se passe au niveau de la classe du FormType en passant des options en 3eme argument de la méthode add : cf [la liste des options de formType](https://symfony.com/doc/current/reference/forms/types.html) ! chaque formType a des options spécifiques

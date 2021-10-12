<?php
namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("admin/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        // on créé un formulaire vierge (sans données initiales car l'objet fournit est vide)
        $userForm = $this->createForm(UserType::class, $user);

        // Après avoir été affiché le handleRequest nous permettra
        // de faire la différence entre un affichage de formulaire (en GET) 
        // et une soumission de formulaire (en POST)
        // Si un formulaire a été soumis, il rempli l'objet fournit lors de la création
        $userForm->handleRequest($request);


        // l'objet de formulaire a vérifié si le formulaire a été soumis grace au HandleRequest
        // l'objet de formulaire vérifie si le formulaire est valide (token csrf mais pas que)
        if ($userForm->isSubmitted() && $userForm->isValid()) {

            // on ne demande l'entityManager que si on en a besoin
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);

            // on récupère le mot de passe depuis le formulaire
            // car on a démappé le champ (c'est à dire que le formulaire ne le gère pas)
            $clearPassword = $request->request->get('user')['password']['first'];
            // si un mot de passe a été saisi
            if (! empty($clearPassword))
            {
                // hashage du mot de passe écrit en clair
                $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            // pour opquast 
            $this->addFlash('success', "User `{$user->getPseudo()}` created successfully");

            // redirection
            return $this->redirectToRoute('admin_user_index');
        }

        
        
        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $userForm,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        // le champ mot de passe est différent en update et en ajout
        // on le rajoute au niveau du controleur
        $form
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class, 
            'required' => false,

            // comme on veut appliquer des règles de gestion non standard
            // on précise à symfony que cette valeur ne correspond à aucun 
            // champ de notre objet
            //!\ il faudra gérer la valeur saisie dans le controleur
            'mapped' => false,
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ]);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // hashage du mot de passe avant l'envoi du formulaire
            $clearPassword = $request->request->get('user')['password']['first'];
            // si un mot de passe a été saisi
            if (! empty($clearPassword))
            {
                // hashage du mot de passe écrit en clair
                $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
                $user->setPassword($hashedPassword);
            }
           
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }
        
        // on fournit ce formulaire à notre vue
        return $this->render('admin/user/add.html.twig', [
            'user_form' => $form->createView(),
            'user' => $user,
            
        ]);
      
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}

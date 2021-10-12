<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('pseudo', null, [
                "label" => "Pseudo",
            ])
            ->add('email')
            ->add('roles', ChoiceType::class, [
                "label" => "Roles",
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true,
            ])

            //on met en place la demande de confirmation de mot de passe    
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                // comme on veut appliquer des règles de gestion non standard
                // on précise à symfony que cette valeur ne correspond à aucun 
                // champ de notre objet
                //!\ il faudra gérer la valeur saisie dans le controleur
                'mapped' => false,
                'required' => false,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            // a utiliser si ne veux utiliser qu'un seul role pour eviter l'erreur array to string
            /* ->add('singleRole', ChoiceType::class, [
                "label" => "Roles",
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => false,
                'expanded' => true
            ]) */;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

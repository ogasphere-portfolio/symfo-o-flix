<?php

namespace App\Form;

use App\Entity\TvShow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('synopsys')
            ->add('image', FileType::class, [
                'label' => 'Image',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
            ])
            ->add('nbLikes')
            ->add('publishedAt')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('categories', CollectionType::class, [
                'entry_type' => CategoryType::class,
                'entry_options' => ['label' => false]])
            ->add('seasons', CollectionType::class, [
                'entry_type' => SeasonType::class,
                'entry_options' => ['label' => false]])
            ->add('plays', CollectionType::class, [
                'entry_type' => PlayType::class,
                'entry_options' => ['label' => false]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TvShow::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\MockUp;
use App\Entity\Portfolio;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\SousCategories;


class ImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_image')

            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'categorie',
                'expanded' => true, // Transforme le select en boutons radio
                'multiple' => false, // Un seul choix possible
                'label' => 'Choisir une catégorie'
            ])
            ->add('sousCategories', EntityType::class, [
                'class' => SousCategories::class,
                'choice_label' => 'type',
                'multiple' => true, // ou false selon ton besoin
                'expanded' => true, // si tu veux des cases à cocher / boutons
                'label' => 'Choisir :sous catégorie'
            ])
            ->add('mock_up', EntityType::class, [
                'class' => MockUp::class,
                'choice_label' => 'type',
                'expanded' => true,
                'multiple' => false,
                'label' => 'Choisir un type de mock Up',


            ])
            ->add('legende')
            ->add('taille')
            ->add('border', ChoiceType::class, [
                'choices' => [
                    'Fantasy' => 'border-fantasy',
                    'Dark Fantasy' => 'border-dark-fantasy',
                    'Sweet Fantasy' => 'border-sweet-fantasy',
                    'Encrage' => 'border-ink',
                    'Aucune bordure' => ' '
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Choisir un type de Bordure'
            ])
            ->add('image', FileType::class, [
                'label' => 'Déposez le fichier ici',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new Assert\File(
                        maxSize: '4024k',
                        extensions: ['jpg'],
                        extensionsMessage: 'Please upload a valid jpg document',
                    )
                ],
            ])


            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}

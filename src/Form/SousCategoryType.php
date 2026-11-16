<?php

namespace App\Form;

use App\Entity\SousCategories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', null, [
                'label' => 'Nom de la sous-catégorie',
                'attr' => ['placeholder' => 'Ex : Dragons', 'maxlength' => 50],
            ])
            ->add('save', SubmitType::class, [
                'label' => $options['submit_label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SousCategories::class,
            'submit_label' => 'Ajouter la sous-catégorie',
        ]);
        $resolver->setAllowedTypes('submit_label', 'string');
    }
}

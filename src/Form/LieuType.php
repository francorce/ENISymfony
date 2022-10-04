<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rue')
            ->add('latitude')
            ->add('longitude')
            ->add('nom_ville', EntityType::class,[
                'class' => 'App\Entity\Ville',
                'label' => ' ',
                'choice_label' => function($ville) {
                    return $ville->getNomVille();
                },
                'multiple' => false,
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}

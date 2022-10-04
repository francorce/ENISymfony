<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('password')
            ->add('email')
            //->add('roles')
            ->add('nom')
            ->add('pseudo')
            ->add('prenom')
            ->add('telephone')
//            ->add('admin')
//            ->add('actif')
            ->add('photo')
            ->add('estRattache', EntityType::class,[
                'class' => 'App\Entity\Site',
                'label' => ' ',
                'choice_label' => function($ville) {
                    return $ville->getNomSite();
                },
                'multiple' => false,
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}

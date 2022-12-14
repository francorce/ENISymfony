<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulationMotifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('nom')
//            ->add('datedebut')
//            ->add('duree')
//            ->add('datecloture')
//            ->add('nbinscriptionsmax')
//            ->add('descriptioninfos')
//            ->add('urlPhoto')
//            ->add('archived')
            ->add('MotifAnnulation')
//            ->add('etat')
//            ->add('lieux')
//            ->add('etablissement')
//            ->add('participant')
//            ->add('participants')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}

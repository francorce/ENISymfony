<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('datedebut')
            ->add('duree')
            ->add('datecloture', DateType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('nbinscriptionsmax')
            ->add('descriptioninfos',)
            ->add('urlPhoto', FileType::class, [
                'label' => 'urlPhoto',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'data_class' => null,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                            'image/jfif',
                            'image/webp',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid file',
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg, .jpeg, .png, .gif, .webp',
                ],
            ])
            //->add('etat')
            ->add('lieux', EntityType::class,[
                'class' => 'App\Entity\Lieu',
                'choice_label' => function($lieux) {
                    return $lieux->nomVilleAndRue();
                },
                'multiple' => false,
                'expanded' => false
            ])
            ->add('etablissement', EntityType::class,[
                'class' => 'App\Entity\Site',
                'label' => 'Campus Organisateur    ',
                'choice_label' => function($participant) {
                    //getPseudo et nom et prenom

                    return $participant->__toString();
                },
                'multiple' => false,
                'expanded' => false
            ])
//            ->add('lieux', CollectionType::class, [
//                'entry_type' => LieuType::class,
//                'allow_add' => true,
//                'allow_delete' => true,
//                'delete_empty' => true,
//                'by_reference' => false,
//                'label' => false
//            ]);
//            ->add('participant', EntityType::class,[
//                'class' => 'App\Entity\Participant',
//                'label' => 'Organisateur    ',
//                'choice_label' => function($participant) {
//                //getPseudo et nom et prenom
//
//                    return $participant->__toString();
//                },
//                'multiple' => false,
//                'expanded' => false
//            ])
            //->add('participants')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('password', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'invalid_message' => 'The password fields must match.',
//                'options' => ['attr' => ['class' => 'password-field']],
//                'required' => true,
//                'first_options' => ['label' => 'Password'],
//                'second_options' => ['label' => 'Repeat Password'],
//            ])
            ->add('email')
            //->add('roles')
            ->add('nom')
            ->add('pseudo')
            ->add('prenom')
            ->add('telephone')
//            ->add('admin')
//            ->add('actif')
//            ->add('photo')
//            ->add('photo', FileType::class, [
//                'label' => 'photo',
//                // unmapped means that this field is not associated to any entity property
//                'mapped' => false,
//
//                // make it optional so you don't have to re-upload the PDF file
//                // every time you edit the Product details
//                'required' => false,
//
//                // unmapped fields can't define their validation using annotations
//                // in the associated entity, so you can use the PHP constraint classes
//                'constraints' => [
//                    new File([
//                        'maxSize' => '1024k',
//                        'mimeTypes' => [
//                            'image/jpg',
//                            'image/png',
//                            'image/jpeg',
//                            'image/jfif',
//                            'image/webp',
//                            'image/gif',
//                        ],
//                        'mimeTypesMessage' => 'Please upload a valid file',
//                    ])
//                ],
//                'attr' => [
//                    'accept' => '.jpg, .jpeg, .png, .gif, .webp',
//                ],
//            ])
            ->add('estRattache', EntityType::class, [
                'class' => 'App\Entity\Site',
                'label' => ' ',
                'choice_label' => function ($ville) {
                    return $ville->getNomSite();
                },
                'multiple' => false,
                'expanded' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUtilisateur',TextType::class, [
                "label"=>"Nom d'utilisateur",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('password',PasswordType::class, [
                "label" => "Mot de passe",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nom',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('prenom',TextType::class, [
                "label" => "Prénom",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //->add('adresse')
            ->add('numeroTelephone',NumberType::class, [
                "label"=>"Téléphone",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //->add('specialite')
            ->add('adresseEmail', EmailType::class, [
                "label" => "Email",
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
                ->add('adresse', TextareaType::class, [
                    "label" => "Adresse",
                    'attr' => [
                        'class' => 'form-control'
                    ]
                    ])
            ->add('role',ChoiceType::class, [
                'label' => 'Je suis ',
                'attr'  => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Patient'                  => 'ROLE_PATIENT',
                    'Médécin'                  => 'ROLE_MEDECIN',
                    'Pharmacie'                => 'ROLE_PHARMACIE',
                    'Centre Imagérie Médicale' => 'ROLE_IMAGERIE_MEDICALE',
                    'Laboratoire'              => 'ROLE_LABORATOIRE'
                ]
            ])
            //->add('photoUrl')
            //->add('facebookUrl')
            //->add('twitterUrl')
            //->add('youtubeUrl')
            //->add('status')
           /* ->add('description')
            ->add('degre')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

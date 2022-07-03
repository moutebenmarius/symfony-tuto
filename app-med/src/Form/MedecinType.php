<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photo', FileType::class, [
            'label' => 'Photo ( Optionel )',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '20M',
                ])
            ],
        ])
            ->add('specialite', ChoiceType::class, [
                'label' => 'Votre spécialité',
                'attr'=>['class'=>"form-control m-2"],
                'choices' => [
                    'Cardiologue (Cœur)'     => 'Cardiologue',
                    'Dentiste (Dents)'       => 'Dentiste',
                    'Dermatologue (Peau) '   => 'Dermatologue',
                    'Généraliste'            => 'Généraliste',
                    'Psychothérapeute '      => 'Psychothérapeute',
                    'Kinésithérapeute '      => 'Kinésithérapeute',
                    'Ophtalmologiste (Yeux)' => 'Ophtalmologiste',
                    'Pédiatre (Enfant)   '   => 'Pédiatre',
                    'Opticien'               => 'Opticien'
                ]
            ])
            ->add('degre', ChoiceType::class, [
                'label' => "Votre dégré",
                'attr'=>['class'=>"form-control m-2"],
                'choices' => [
                    'Mastére'     => 'Mastere',
                    'Docteur'       => 'Docteur',
                    'Professour'   => 'Professour'
                ]
            ])
           
            
            ->add('facebookUrl', UrlType::class, [
                'required' => false,
                'empty_data' => 'NOT_DEFINED',
                'label' => "indiquer le lien de votre page facebook",
                'attr' => ['class'=>'form-control mt-2', 'placeholder' => "ce champ n'est pas obligatoire"]
            ])
            ->add('twitterUrl', UrlType::class, [
                'required' => false,
                'empty_data' => 'NOT_DEFINED',
                'label' => "indiquer le lien de votre page twitter",
                'attr' => ['class'=>'form-control', 'placeholder' => "ce champ n'est pas obligatoire"]
            ])
            ->add('youtubeUrl', UrlType::class, [
                'required' => false,
                'empty_data' => 'NOT_DEFINED',
                'label' => "indiquer le lien de votre chaine youtube",
                'attr' => ['class'=>'form-control', 'placeholder' => "ce champ n'est pas obligatoire"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

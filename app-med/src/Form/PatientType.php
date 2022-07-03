<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUtilisateur', TextType::class, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class'=>'form-control']
            ]) ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class'=>'form-control']
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class'=>'form-control']
            ])
           
            ->add('adresse', TextareaType::class, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('numeroTelephone', TextType::class, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('adresseEmail', EmailType::class, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('photo', FileType::class, [
                'attr' => ['class'=>'form-control'],
                'label' => 'Photo ( Optionel )',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new ConstraintsFile([
                        'maxSize' => '20M',
                    ])
                ]
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

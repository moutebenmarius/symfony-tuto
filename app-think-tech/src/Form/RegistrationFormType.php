<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photo', FileType::class, [
            'attr' => [
                'class' => 'form-control',
                'required'   => false,
                'empty_data' => 'avatar.jpg',
            ]
        ])
        ->add('cin', TextType::class, [
            'label' => 'CIN',
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('nom', TextType::class, [
           
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('dateNaissance', DateType::class, [
            'label' => "Date de naissance",
            'widget' => 'choice',
            'years'  =>  range(1998,2010),
        ])
      
        ->add('genre', ChoiceType::class, [
            'choices' => [
                'Homme' => 'homme',
                'Femme' => 'femme'
            ],
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        
       
        ->add('adresse', TextareaType::class, [
           
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('telephone', NumberType::class, [
            'label' => 'Téléphone',
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('niveau', TextType::class, [
            
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('universite', TextType::class, [
           'label' => 'Université',
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('ville', TextType::class, [
           
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('email', EmailType::class, [
            'attr' => [
                'class' => 'form-control'
            ] 
        ])
        ->add('password', PasswordType::class, [
            'label' => 'Mot de passe',
            'attr' => [
                'class' => 'form-control'
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

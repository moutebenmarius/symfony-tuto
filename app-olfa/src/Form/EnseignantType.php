<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           /* ->add('photoUrl', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])*/
            ->add('cin', TextType::class, [
                'label' => 'CIN',
                'attr'=>['class'=>'form-control']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr'=>['class'=>'form-control']
            ])
            ->add('nom', TextType::class, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr'=>['class'=>'form-control']
            ])
            ->add('genre', ChoiceType::class, [
                'label'   => 'Genre',
                'choices' => [
                    'Femme' => 'Femme',
                    'Homme' => 'Homme' 
                ],
                'attr'=>['class'=>'form-control']
            ])
            ->add('dateNaissance', DateType::class, [
                'attr'=>['class'=>'form-control'],
                'widget' => 'choice',
                'years'  => range(1970, 2000) 
            ])
            ->add('telephone', NumberType::class, [
                'label' => 'Téléphone',
                'attr'=>['class'=>'form-control']
            ])
            ->add('email', EmailType::class, [
                'attr'=>['class'=>'form-control']
            ])
           ->add('specialite', TextType::class, [
                 'label' => 'Spécialité',
                'attr' => ['class'=>'form-control']
            ])
            ->add('diplome', FileType::class, [
                'label' => "Diplôme",
                'attr' => ['class'=>'form-control']
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

<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProviseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', TextType::class, [
                'label' => 'CIN',
                'attr'=>['class'=>'form-control']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr'=>['class'=>'form-control']
            ])
            ->add('nom', TextType::class, [
                'label' => "Nom",
                'attr'=>['class'=>'form-control']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr'=>['class'=>'form-control']
            ])
            ->add('genre', ChoiceType::class, [
                'label'   => 'Genre', // affucgage
                'choices' => [
                    'Homme' => 'Homme', // elli 3al imeen homma elli bech yetsajlou fi base
                    'Femme' => 'Femme' 
                ],
                'attr'=>['class'=>'form-control']
            ])
            ->add('dateNaissance', DateType::class, [
                //'widget' => 'single_text',
                'attr'=>['class'=>'form-control'],
                'widget' => 'choice',
                'years'  => range(1970,1990)
            ])
            ->add('telephone', NumberType::class, [
                'label' => "Téléphone",
                'attr'=>['class'=>'form-control']
            ])
            ->add('email', EmailType::class, [
                'attr'=>['class'=>'form-control']
            ])
           /* ->add('specialite', TextType::class, [
                'attr' => ['class'=>'form-control']
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

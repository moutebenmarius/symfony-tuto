<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PharmacieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUtilisateur',TextType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('password',PasswordType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('nom',TextType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('prenom',TextType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('adresse',TextareaType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('numeroTelephone',TextType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('description',TextareaType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('adresseEmail',TextareaType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('photoUrl', FileType::class)
            ->add('facebookUrl', UrlType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('twitterUrl', UrlType::class, [
                'attr'=>['class'=>"form-control m-2"]
            ])
            ->add('youtubeUrl', UrlType::class, [
                'attr'=>['class'=>"form-control m-2"]
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

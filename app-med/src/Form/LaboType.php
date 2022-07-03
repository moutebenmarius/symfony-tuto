<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LaboType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUtilisateur')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('numeroTelephone')
            ->add('description')
            ->add('adresseEmail')
            ->add('photoUrl')
            ->add('facebookUrl')
            ->add('twitterUrl')
            ->add('youtubeUrl')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

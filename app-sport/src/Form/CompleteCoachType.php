<?php

namespace App\Form;

use App\Entity\TypeSport;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompleteCoachType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('competences', TextareaType::class, [
                'label' => 'Présentez-vous en quelque mots',
                'attr' => [
                    
                    'class' => 'form-control'
                ]
            ])
            ->add('typeSport', EntityType::class, [
                'class' => TypeSport::class,
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

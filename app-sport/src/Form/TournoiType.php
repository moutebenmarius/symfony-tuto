<?php

namespace App\Form;

use App\Entity\Tournoi;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('candidat', EntityType::class, [
                'label' => "Séléctionner le premier participant",
                'class' => Utilisateur::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.role = :val')->setParameter(':val', 'ROLE_ADHERENT');
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            /*  ->add('vainqueur', TextType::class, [
                        'attr' => [
                            'class' => 'f'
                            ]
                            ])*/
            // ->add('score')
            ->add('candidat2', EntityType::class, [
                'label' => "Séléctionner le second participant",
                'class' => Utilisateur::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.role = :val')->setParameter(':val', 'ROLE_ADHERENT');
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateTournoi', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de tournoi',
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournoi::class,
        ]);
    }
}

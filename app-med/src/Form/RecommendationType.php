<?php

namespace App\Form;

use App\Entity\Recommendation;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecommendationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('medecin',null,[
                'label' => "Médecin",
                'query_builder' => function (UtilisateurRepository $er) {
                    return $er->trouverTousLesMedecin();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('labo',null,[
                'label' => "Labratoire",
                'query_builder' => function (UtilisateurRepository $er) {
                    return $er->trouverTousLesLabo();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('pharmacie',null,[
                'label' => 'Pharmacie',
                'query_builder' => function (UtilisateurRepository $er) {
                    return $er->trouverTousLesPharamacies();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('centreImagerie',null,[
                'label' => "Centre d'imagérie",
                'query_builder' => function (UtilisateurRepository $er) {
                    return $er->trouverTousLesCentres();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recommendation::class,
        ]);
    }
}

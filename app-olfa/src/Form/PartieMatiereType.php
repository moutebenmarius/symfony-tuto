<?php

namespace App\Form;

use App\Entity\PartieMatiere;
use App\Repository\MatiereRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartieMatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        /*->add('cahierTexte', null, [
            'label' => 'Séléctionner le cahier de texte',
            'attr' => [
                'class' => 'form-control'
            ]
        ])*/
            ->add('enseignant', null, [
                'query_builder' => function(UtilisateurRepository $utilisateurRepository){
                    return $utilisateurRepository->findAllTeachers();
                },
                'label' => "Séléctionner l'enseignant",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('matiere', null, [
                'label' => "Sélétionner la matière", 
                'query_builder' => function(MatiereRepository $matiereRepository){
                    return $matiereRepository->getNonArchivedMatiere();
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
            'data_class' => PartieMatiere::class,
        ]);
    }
}

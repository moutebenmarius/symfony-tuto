<?php

namespace App\Form;

use App\Entity\Programme;
use App\Repository\ClasseRepository;
use App\Repository\MatiereRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('classe', null, [
            'attr' => [
                'class' => 'form-control'
            ],'query_builder' => function(ClasseRepository $classeRepository){
                return $classeRepository->getNonArchivedClasses();
            }
        ])
        ->add('enseignant', null, [
            'attr' => [
                'class' => 'form-control'
            ],
            'query_builder' => function(UtilisateurRepository $utilisateurRepository){
                return $utilisateurRepository->findAllTeachers();
            }
        ])
            ->add('fichierPdf', FileType::class, [
                'label' => 'importer fichier du programme',
                'attr' => [
                    'class' => 'form-control'
                ] 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}

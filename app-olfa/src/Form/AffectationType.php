<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Repository\ClasseRepository;
use App\Repository\MatiereRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('enseignant', null, [
                'query_builder' => function(UtilisateurRepository $utilisateurRepository){
                    return $utilisateurRepository->findAllTeachers();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('classe',null,[
                'query_builder' => function(ClasseRepository $classeRepository){
                    return $classeRepository->getNonArchivedClasses();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('matiere',null,[
                'label' => 'Matière',
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
            'data_class' => Affectation::class,
        ]);
    }
}

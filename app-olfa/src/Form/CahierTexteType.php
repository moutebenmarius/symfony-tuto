<?php

namespace App\Form;

use App\Entity\CahierTexte;
use App\Repository\ClasseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CahierTexteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           /* ->add('libelle', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])*/
            ->add('classe', null, [
                'label' => "Séléctionner cahier du texte",
                'query_builder' => function(ClasseRepository $classeRepository){
                    return $classeRepository->getNonArchivedClasses();
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
            'data_class' => CahierTexte::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Repository\ClasseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [
                'attr'=>['class'=>'form-control'],
                /*'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/jpg',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],*/
            ])
            ->add('matricule', TextType::class, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('cinParent', TextType::class, [
                'label' => 'CIN Parent',
                'attr'=>['class'=>'form-control']
            ])
            ->add('nom', TextType::class, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr'=>['class'=>'form-control']
            ])
            ->add('genre', ChoiceType::class, [
                'attr'=>['class'=>'form-control'],
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme'  => 'Femme'
                ]
            ])
            ->add('dateNaissance', DateType::class, [
                'attr' =>[
                    'class'=> 'form-control'
                ],
                'widget' => 'choice', // ta3malli calendrier
                'years'  => range(1991, 2010) // 1997 - 2010
            ])
            ->add('adresse', TextareaType::class,[
                'attr' =>['class'=> 'form-control']
            ] )
            ->add('telephoneParent',  TextType::class, [
                'label' => 'Téléphone Parent',
                'attr'=>['class'=>'form-control']
            ])
            ->add('classe', null, [
                'query_builder' => function(ClasseRepository $classeRepository){
                    return $classeRepository->getNonArchivedClasses();
                },
                'attr' => ['class'=> 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Formation;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photo', FileType::class,array('data_class' => null,'required' => false))
            ->add('titre', TextType::class, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('description', TextareaType::class, [
                'attr'=> ['class'=>'form-control']
            ])
            ->add('dateDebut', DateType::class, [
                'label' => "Date de lancement",
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('nbrHeures', NumberType::class, [
                'label' => "Nombre d'heures",
                'attr'=>['class'=>'form-control']
            ])
            ->add('type', ChoiceType::class, [
                'attr'=>['class'=>'form-control'],
                'choices' => [
                    'Présentiel' => 'Présentiel',
                    'En ligne'   => 'En ligne'
                ]
            ])
            ->add('formateur', null, [
                'query_builder' => function(UtilisateurRepository $er) {
                    return $er->getAllFormateurs();
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
            'data_class' => Formation::class,
        ]);
    }
}

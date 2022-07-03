<?php

namespace App\Form;

use App\Entity\Eleve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Eleve1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'Masuclin' => 'masculin',
                'Féminin'  => 'féminin'
            ]
        ])
        ->add('dateNaissance', DateType::class, [
            'attr' =>['class'=> 'form-control']
            ,
                'widget' => 'single_text',
        ])
        ->add('adresse', TextareaType::class,[
            'attr' =>['class'=> 'form-control']
        ] )
        ->add('telephoneParent',  TextType::class, [
            'attr'=>['class'=>'form-control']
        ])
        ->add('classe', null, [
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

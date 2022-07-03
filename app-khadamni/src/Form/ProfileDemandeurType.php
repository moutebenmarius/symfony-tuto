<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileDemandeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('nom', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('prenom' , TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('cin', NumberType::class, ['attr' => ['class' => 'form-control']])
            ->add('adresse', TextareaType::class, ['attr' => ['class' => 'form-control']])
          
            ->add('numeroTelephone', NumberType::class, ['attr' => ['class' => 'form-control']])
            //->add('photoUrl')
            //->add('fichierUrl')
            ->add('resume', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('experiences', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('competences', TextareaType::class, ['attr' => ['class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Solution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, array('data_class' => null,'required' => false))
            ->add('titre', TextType::class, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('prix', TextType::class, [
                'attr' => ['class'=>'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solution::class,
        ]);
    }
}

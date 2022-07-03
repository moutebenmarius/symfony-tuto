<?php

namespace App\Form;

use App\Entity\DossierMedical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierMedicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ficheDescription', TextareaType::class, [
                'label' => 'Description (*)',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('ordonnance', TextareaType::class, [
                'required'   => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('analyses', TextareaType::class, [
                'required'   => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('radio', TextareaType::class, [
                'required'   => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierMedical::class,
        ]);
    }
}

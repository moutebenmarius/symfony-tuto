<?php

namespace App\Form;

use App\Entity\Seance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateSeance', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Choisir une date',
                'attr'  => [
                    'class' => 'form-control'
                ]
            ])
            ->add('limitAdherent', IntegerType::class, [
                'label' => "Nombre max d'adhérents",
                'attr'  => [
                    'class' => 'form-control'
                ]
            ])
            //->add('status')
           // ->add('coach')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('email', EmailType::class, ['attr'=>['class'=>'form-control mt-2'], 'label'=>"Email"])
            ->add('password', PasswordType::class, ['attr'=>['class'=>'form-control', 'label'=>"Mot de passe"]])
            ->add('role', ChoiceType::class, [
                'choices'=> ['Je veux recruter' => 'ROLE_RECRUTEUR', 
                'Je veux trouver un emploi' => 'ROLE_DEMANDEUR'],
                'attr'=>['class'=>'form-control']])
            ->add('nom', TextType::class, ['attr'=>['class'=>'form-control']])
            ->add('prenom', TextType::class, ['label'=> "Prénom",'attr'=>['class'=>'form-control']])
            //->add('cin', TextType::class, ['attr'=>['class'=>'form-control']])
            ->add('adresse', TextareaType::class, ['attr'=>['class'=>'form-control']])
            ->add('numeroTelephone', NumberType::class, [ 'label'=> "Numéro de téléphone", 'attr'=>['class'=>'form-control']])
            //->add('description', TextareaType::class, ['attr'=>['class'=>'form-control']])
          
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

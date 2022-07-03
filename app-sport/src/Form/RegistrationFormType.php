<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe'
            ])
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class, [
                'label' => 'Prénom'
            ])
            //->add('competences')
            
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    
                    'html5' => false,
                    'format' => 'yyyy-MM-dd',
                ]
                
            ])
            ->add('adresse', TextareaType::class)
            ->add('telephone', NumberType::class,[
                'label' => 'Téléphone'
            ])
            ->add('genre',ChoiceType::class, [
                'label'   => "gender",
                'choices' => [
                    'Féminin'                => "feminin",
                    'Masculin'               => "masculin"
                ],
                'attr' => ['class'=>'form-control']
            ] )
            ->add('role', ChoiceType::class, [
                'label' => 'Vous êtes',
                'choices' => [
                    'Je suis un coach'    => "ROLE_COACH",
                    'Je suis un adhérent' => "ROLE_ADHERENT"
                ],
                'attr' => ['class'=>'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

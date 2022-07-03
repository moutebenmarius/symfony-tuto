<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{   
    private $encoder;
    private $manager;
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->manager = $entityManager;
    }
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request): Response
    {
  
        $user = new Utilisateur();
        $user->setEstSupprime(false);
        $user->setStatus("en_attente");
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($user->getRole() === "ROLE_RECRUTEUR") {
              
                $this->manager->persist($user);
                $this->manager->flush();
                return $this->redirectToRoute('recruteur', ['recruteur'=>$user->getId()]);
            }else{
                $this->manager->persist($user);
                $this->manager->flush();
                return $this->redirectToRoute('demandeur', ['demandeur'=>$user->getId()]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/completer/recruteur/{recruteur}", name="recruteur")
     */
    public function recruteur(Request $request, Utilisateur $recruteur): Response
    {
        // formulaire de recruteur
        $form = $this->createFormBuilder(null)
        ->add('photoUrl', FileType::class, ['required' => false, 'label' => 'Photo(Optionel): ','attr'=>['class'=>'form-control']])
        ->add('cin', NumberType::class, ['label'=>"CIN",'attr'=>['class'=>'form-control']])
       // ->add('numeroTelephone', NumberType::class, ['label'=>"Numéro de Téléphone",'attr'=>['class'=>'form-control']])
        ->add('description', TextareaType::class, ['label'=>"Présentez-vous en quelque ligne: ",'attr'=>['class'=>'form-control']])
        ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photoUrl')->getData();
          
            if(!$photo){
                $recruteur->setPhotoUrl("avatar-standard.png");
            }else{
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
             
                $newFilename = 'avatar-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('avatars'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $recruteur->setPhotoUrl($newFilename); 
            }
            //dd($recruteur);
            $hash = $this->encoder->encodePassword($recruteur, $recruteur->getPassword());
            $recruteur->setPassword($hash);
            $recruteur->setDescription($form->getData()['description']);
            $recruteur->setCin($form->getData()['cin']);
            
            $this->manager->flush();
            $this->addFlash("info", "l'inscription se faite avec succée");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/profil-recruteur.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/completer/demandeur/{demandeur}", name="demandeur")
     */
    public function demandeur(Request $request, Utilisateur $demandeur): Response
    {
        $form = $this->createFormBuilder(null)
        
        ->add('photoUrl', FileType::class, ['required' => false, 'label' => 'Photo(Optionel): ','attr'=>['class'=>'form-control']])
        ->add('cin', NumberType::class, ['label'=>"CIN",'attr'=>['class'=>'form-control']])
       // ->add('numeroTelephone', NumberType::class, ['label'=>"Numéro de Téléphone",'attr'=>['class'=>'form-control']])
        ->add('resume', TextareaType::class, ['label'=>"Présentez-vous en quelque ligne: ",'attr'=>['class'=>'form-control']])
        ->add('experiences', TextareaType::class, ['label'=>"Identifier vos experiences: ",'attr'=>['class'=>'form-control']])
        ->add('competences', TextareaType::class, ['label'=>"Identifier vos competences: ",'attr'=>['class'=>'form-control']])
        ->add('fichierUrl', FileType::class, ['label'=>"Votre CV(en format PDF): ",'attr'=>['class'=>'form-control']])
        ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photoUrl')->getData();
          
            if(!$photo){
                $demandeur->setPhotoUrl("avatar-standard.png");
            }else{
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
             
                $newFilename = 'avatar-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('avatars'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $demandeur->setPhotoUrl($newFilename); 
            }
            $fichierCv = $form->get('fichierUrl')->getData();
            $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $name = $demandeur->getNom().'-'.$demandeur->getPrenom();
                $newFilename = 'cv-'.$name.'-'.uniqid().'.'.$fichierCv->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichierCv->move(
                        $this->getParameter('cvs'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $demandeur->setFichierUrl($newFilename);

            $hash = $this->encoder->encodePassword($demandeur, $demandeur->getPassword());
            $demandeur->setPassword($hash);
            $demandeur->setResume($form->getData()['resume']);
            $demandeur->setCompetences($form->getData()['competences']);
            $demandeur->setExperiences($form->getData()['experiences']);
            $demandeur->setCin($form->getData()['cin']);
           
            $this->manager->flush();
            $this->addFlash("info", "l'inscription se faite avec succée");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/profil-recruteur.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}

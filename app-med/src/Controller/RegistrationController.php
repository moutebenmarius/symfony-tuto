<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\MedecinType;
use App\Form\RegistrationFormType;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function inscrire(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager): Response
    {
       $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            
            $user->setYoutubeUrl('');
            $user->setTwitterUrl(NULL);
            $user->setFacebookUrl(NULL);
            $user->setStatus("EN_COURS");
            
            $entityManager->persist($user);
            $entityManager->flush();
            if($user->getRole() === "ROLE_MEDECIN"){
                return $this->redirectToRoute('completer_profil_medecin', ['id' => $user->getId()]);
            }
            // do anything else you need here, like send an email
            $this->addFlash("success", "Vous pouvez connecter maintenant !");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/completer-profil-medecin/{id}", name="completer_profil_medecin")
     */
    public function completer_profil_medecin(Utilisateur $medecin, Request $request, EntityManagerInterface $entityManager): Response
    {
       
        $form = $this->createForm(MedecinType::class, $medecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = 'medecin-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $medecin->setPhotoUrl($newFilename);
            }else{
                $medecin->setPhotoUrl("doctor-standard.jpg");
            }
           // dd($medecin);

            $entityManager->flush();
   
            $this->addFlash("success", "Vous pouvez connecter maintenant !");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/completer-profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

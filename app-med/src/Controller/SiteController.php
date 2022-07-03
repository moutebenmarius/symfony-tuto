<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\MedecinType;
use App\Form\PatientType;
use App\Form\PharmacieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\String\Slugger\SluggerInterface;
class SiteController extends AbstractController
{
    /**
     * @Route("/", name="app_site")
     */
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/medecins", name="site_medecins")
     */
    public function medecins(): Response
    {
        $doctrine = $this->getDoctrine();
        $repo     = $doctrine->getRepository(Utilisateur::class);
        $doctors  = $repo->findBy(['role'=>"ROLE_MEDECIN"]);
        //dd($doctors); pour debugging
        return $this->render('site/medecins.html.twig', [
            'doctors' => $doctors,
        ]);
    }

    /**
     * @Route("/pharmacies", name="site_pharmacies")
     */
    public function pharmacies(): Response
    {
        $doctrine = $this->getDoctrine();
        $repo     = $doctrine->getRepository(Utilisateur::class);
        $pharmacies  = $repo->findBy(['role'=>"ROLE_PHARMACIE"]);
        return $this->render('site/pharmacies.html.twig', [
            'pharmacies' => $pharmacies,
        ]);
    }

     /**
     * @Route("/laboratoires", name="site_laboratoires")
     */
    public function laboratoires(): Response
    {
        $doctrine = $this->getDoctrine();
        $repo     = $doctrine->getRepository(Utilisateur::class);
        $laboratoires  = $repo->findBy(['role'=>"ROLE_LABORATOIRE"]);
        return $this->render('site/laboratoires.html.twig', [
            'laboratoires' => $laboratoires,
        ]);
    }

    /**
     * @Route("/imagerie_medicale", name="site_imagerie_medicale")
     */
    public function imagerieMedicale(): Response
    {
        $doctrine = $this->getDoctrine();
        $repo     = $doctrine->getRepository(Utilisateur::class);
        $images  = $repo->findBy(['role'=>"ROLE_IMAGERIE_MEDICALE"]);
        return $this->render('site/imagerie_medicale.html.twig', [
            'images' => $images,
        ]);
    }


     /**
     * @Route("/incription-medecin", name="inscription_medecin")
     */
    public function inscriptionMedecin(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $medecin = new Utilisateur();
        $medecin->setRole('ROLE_MEDECIN');
        $medecin->setStatus('EN_COURS');
        $formulaire = $this->createForm(MedecinType::class, $medecin);
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid()){
            $photoUrl = $formulaire->get('photo')->getData();
            if($photoUrl){
                $originalFilename = pathinfo($photoUrl->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                //$safeFilename = $slugger->slug($originalFilename);
                $newFilename = 'medecin-'.uniqid().'.'.$photoUrl->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoUrl->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $medecin->setPhotoUrl($newFilename);
                //dd($medecin);
            }else{
                $medecin->setPhotoUrl('medecin.png');
            }
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();
            $password = $formulaire->get('password')->getData();
            $hash     = $encoder->encodePassword($medecin, $password);
            $medecin->setPassword($hash);
            //dd($medecin);
            $manager->persist($medecin);
            $manager->flush();
            $this->addFlash('info',"L'inscription se faite avec success");
           return $this->redirectToRoute('app_login');
        }
        return $this->render('site/inscription.html.twig', [
            'formulaire' => $formulaire->createView(),
            'titre'      => "Inscription Médecin"
        ]);
    }


     /**
     * @Route("/incription-pharmacie", name="inscription_pharmacie")
     */
    public function inscriptionPharmacie(Request $request): Response
    {
       $pharmacie = new Utilisateur();
       $form      = $this->createForm(PharmacieType::class, $pharmacie);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
        $photoUrl = $form->get('photo')->getData();
            if($photoUrl){
                $originalFilename = pathinfo($photoUrl->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                //$safeFilename = $slugger->slug($originalFilename);
                $newFilename = 'medecin-'.uniqid().'.'.$photoUrl->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoUrl->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $pharmacie->setPhotoUrl($newFilename);
                //dd($medecin);
            }else{
                $pharmacie->setPhotoUrl('medecin.png');
            }
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();
            $password = $form->get('password')->getData();
            $hash     = $pharmacie->encodePassword($pharmacie, $password);
            $pharmacie->setPassword($hash);
            //dd($medecin);
            $manager->persist($pharmacie);
            $manager->flush();
            $this->addFlash('info',"L'inscription se faite avec success");
           return $this->redirectToRoute('app_login');
       } 
       return $this->render('site/inscription.html.twig', [
            'formulaire' => $form->createView(),
            
            'titre'      => "Inscription Pharmacie"
        ]);
    }

     /**
     * @Route("/incription-labo", name="inscription_labo")
     */
    public function inscriptionLaboratoire(): Response
    {
       
        return $this->render('site/inscription.html.twig', [
            'formulaire' => "",
            'titre'      => "Laboratoire"
        ]);
    }


     /**
     * @Route("/incription-centre", name="inscription_centre")
     */
    public function inscriptionCentre(): Response
    {
       
        return $this->render('site/inscription.html.twig', [
            'formulaire' => "",
            'titre'      => "Centre"
        ]);
    }


     /**
     * @Route("/incription-patient", name="inscription_patient")
     */
    public function inscriptionPatient(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $patient = new Utilisateur();
        $patient->setRole("ROLE_PATIENT");
        $form   = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $photoUrl = $form->get('photo')->getData();
            if($photoUrl){
                $originalFilename = pathinfo($photoUrl->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                //$safeFilename = $slugger->slug($originalFilename);
                $newFilename = 'patient-'.uniqid().'.'.$photoUrl->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoUrl->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $patient->setPhotoUrl($newFilename);
                //dd($medecin);
            }else{
                $patient->setPhotoUrl('medecin.png');
            }
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();
            $password = $form->get('password')->getData();
            $hash     = $encoder->encodePassword($patient, $password);
            $patient->setPassword($hash);
            //dd($medecin);
            $manager->persist($patient);
            $manager->flush();
            $this->addFlash('info',"L'inscription se faite avec success");
           return $this->redirectToRoute('app_login');
        }
        return $this->render('site/inscription.html.twig', [
            'formulaire' => $form->createView(),
            "titre"      => "Inscription Patient"
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\DossierMedical;
use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use App\Form\RendezVousType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    /**
     * @Route("/patient", name="app_patient")
     */
    public function index(): Response
    {
        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }

    /**
     * @Route("/liste_medecins", name="patient_liste_medecins")
     */
    public function medecins(): Response
    {
        $medecins = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(['role' => "ROLE_MEDECIN"]);
        return $this->render('patient/medecins.html.twig', [
            'medecins' => $medecins
        ]);
    }

    /**
     * @Route("/rendez-vous-medecin/{id}", name="patient_rdv_medecin")
     */
    public function rendezVousMedecin(Request $request,Utilisateur $medecin): Response
    {
        $connectedPatient = $this->getUser();
        $rendezvous = new RendezVous();
        $rendezvous->setPatient($connectedPatient);
        $rendezvous->setMedecin($medecin);
        $rendezvous->setStatus("en_attente");
        $rendezvous->setDateAjout(new DateTime());
        $form = $this->createForm(RendezVousType::class, $rendezvous);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           
            $doctrine = $this->getDoctrine();
            $manager  = $doctrine->getManager();
            $manager->persist($rendezvous);
            $manager->flush();
            $this->addFlash('info', "votre rendez vous a ete enregistre");
            return $this->redirectToRoute('mes_rendez_vous');
        }
    
        return $this->render('patient/prendre-rdv.html.twig', [
            'medecin' => $medecin,
            'form'    => $form->createView()
        ]);
    }

    /**
     * @Route("/mes-rendezvous", name="mes_rendez_vous")
     */
    public function mesRendezVous(): Response
    {
        $connectedPatient = $this->getUser();
        $mesRdv = $this->getDoctrine()->getRepository(RendezVous::class)->findBy(['patient' => $connectedPatient, 'status' => 'en_attente']);
       
        return $this->render('patient/mes-rendezvous.html.twig', [
            'mesrdv' => $mesRdv
        ]);
    }

    /**
     * @Route("/annuler-rdv/{id}", name="annuler_rendez_vous")
     */
    public function annuler(RendezVous $rendezVous){
        $rendezVous->setStatus("annuler");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('mes_rendez_vous');
    }

    /**
     * @Route("/prolonger-rdv/{id}", name="prolonger_rdv")
     */
    public function prolonger(RendezVous $rendezVous, Request $request){
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->redirectToRoute('mes_rendez_vous');
        }
        
        return $this->render('patient/prolonger-rdv.html.twig', [
            'form' => $form->createView(),
            'medecin' => $rendezVous->getMedecin()
        ]);
    }

    /**
     * @Route("/mes-dossiers", name="mes_dossiers")
     */
    public function mesDossiers(){
        $patientConnecte = $this->getUser(); // utilisateur connecte
        $rendezVous = $this->getDoctrine()->getRepository(RendezVous::class)->findBy(['patient'=>$patientConnecte]);
        $listeDossiers = [];
        foreach($rendezVous as $rdv){
            $dossiers = $rdv->getDossierMedicals();
           
            foreach($dossiers as $dossier){
                array_push($listeDossiers, $dossier);
            }
        }
        return $this->render('patient/mes-dossiers.html.twig', [
            'dossiers' => $listeDossiers
        ]);
    }
}

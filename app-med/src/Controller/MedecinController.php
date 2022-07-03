<?php

namespace App\Controller;

use App\Entity\DossierMedical;
use App\Entity\Recommendation;
use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use App\Form\DossierMedicalType;
use App\Form\FeedbackType;
use App\Form\RecommendationType;
use App\Form\RendezVousType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedecinController extends AbstractController
{
    /**
     * @Route("/medecin", name="app_medecin")
     */
    public function index(): Response
    {
        return $this->render('medecin/index.html.twig', [
            'controller_name' => 'MedecinController',
        ]);
    }

    /**
     * @Route("/rendez-vous-medcins", name="rendez_vous_medecin")
     */
    public function rendrezVousMedecin(): Response
    {
        $medecinConnecte = $this->getUser();
        $rendezvous = $this->getDoctrine()->getRepository(RendezVous::class)->findBy(['status' => 'en_attente', 'medecin' => $medecinConnecte]);

        return $this->render('medecin/rendez-vous-medecins.html.twig', [
            'mesrdv' => $rendezvous
        ]);
    }

    /**
     * @Route("/confirmer-rdv/{id}", name="confirmer_rendez_vous")
     */
    public function confirmerRdv(RendezVous $rendezvous): Response
    {
        $rendezvous->setStatus("confirme");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('rendez_vous_medecin');
    }

    /**
     * @Route("/refuser-rdv/{id}", name="refuser_rdv")
     */
    public function refuserRdv(RendezVous $rendezvous): Response
    {
        $rendezvous->setStatus("refuse");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('rendez_vous_medecin');
    }


    /**
     * @Route("/mes-consultations", name="mes_consultation")
     */
    public function mesConsultations(): Response
    {
        $medecinConnecte = $this->getUser();
        $consultations = $this->getDoctrine()->getRepository(RendezVous::class)->findBy(['medecin' => $medecinConnecte, 'status' => "confirme"]);
        return $this->render('medecin/mes-consultations.html.twig', [
            'consultations' => $consultations,
            'currentDate'   => new DateTime('now')
        ]);
    }


    /**
     * @Route("/consultation/{id}", name="consultation")
     */
    public function consultation(RendezVous $rendezVous, Request $request): Response
    {
        $dossierMedical = new DossierMedical();
        $dossierMedical->setDateConsultation(new \DateTime());
        $dossierMedical->setRendezVous($rendezVous);
        $dossierMedical->setStatus("confirme_medecin");
        $form = $this->createForm(DossierMedicalType::class, $dossierMedical);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($dossierMedical);
            $manager->flush();
            return $this->redirectToRoute('recommendation_patient', ['id' => $dossierMedical->getId()]);

            //return $this->redirectToRoute('mes_consultation');
        }
        return $this->render('medecin/consultation.html.twig', [
            'form' => $form->createView(),
            'patient' => $rendezVous->getPatient()
        ]);
    }

    /**
     * @Route("/recommendation/{id}", name="recommendation_patient")
     */
    public function recommendation(DossierMedical $dossierMedical, Request $request)
    {
        $medecinConnecte = $this->getUser();
        $recommendation = new Recommendation();
        $recommendation->setDossier($dossierMedical);
        $recommendation->setStatus('pas valider');
        $form = $this->createForm(RecommendationType::class, $recommendation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            if ($recommendation->getMedecin() === $medecinConnecte) {
                $manager->persist($recommendation);
                $manager->flush();
                return $this->redirectToRoute('fixer_un_rdv', ['id' => $dossierMedical->getRendezVous()->getPatient()->getId()]);
            } else {
                $dossierMedical->getRendezVous()->setStatus("termine");
                $manager->persist($recommendation);
                $manager->flush();
                return $this->redirectToRoute('mes_consultation');
            }
            dd($form->getData());
            return $this->redirectToRoute('mes_consultation');
        };
        return $this->render('medecin/recommendation.html.twig', ['form' => $form->createView()]);
    }



    /**
     * @Route("/fixer/rendezvous/{id}", name="fixer_un_rdv")
     */
    public function fixerRdv(Utilisateur $patient, Request $request)
    {
        $medecinConnecte = $this->getUser();
        $rendezVous = new RendezVous();
        $rendezVous->setStatus('confirme');
        $rendezVous->setPatient($patient);
        $rendezVous->setMedecin($medecinConnecte);
        $rendezVous->setDateAjout(new \Datetime());
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($rendezVous);
            $manager->flush();
            return $this->redirectToRoute('mes_consultation');
        };
        return $this->render('medecin/fixer-rdv.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/recommendations", name="recommendations")
     */
    public function recommendations()
    {
        $medecinConnecte = $this->getUser();
        $recommendations = $this->getDoctrine()->getRepository(Recommendation::class)->findBy(['medecin' => $medecinConnecte, 'status' => 'pas valider']);
        $recs = [];
        foreach ($recommendations as $rec) {
            array_push($recs, [
                'recommendation_id' => $rec->getId(),
                'description'       => $rec->getDescription(),
                'patient' => [
                    'patient_id' => $rec->getDossier()->getRendezVous()->getPatient()->getId(),
                    'nom' => $rec->getDossier()->getRendezVous()->getPatient()->getNom(),
                    'prenom' => $rec->getDossier()->getRendezVous()->getPatient()->getPrenom()
                ],
                'medecin' => [
                    'medecin_id' => $rec->getDossier()->getRendezVous()->getMedecin()->getId(),
                    'nom' => $rec->getDossier()->getRendezVous()->getMedecin()->getNom(),
                    'prenom' => $rec->getDossier()->getRendezVous()->getMedecin()->getPrenom()
                ]
            ]);
        }
       
        return $this->render('medecin/recommendations.html.twig', ['recommendations' => $recs]);
    }


    /**
     * @Route("/remplir-feedback/{id}", name="feeback")
     */
    public function remplirRecommendation(Recommendation $recommendation, Request $request)
    {
        
        $form = $this->createForm(FeedbackType::class, $recommendation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $recommendation->setStatus("valider");
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->redirectToRoute('recommendations');
        }   
        
        return $this->render('medecin/feedback.html.twig', ['form' => $form->createView()]);
    }
}

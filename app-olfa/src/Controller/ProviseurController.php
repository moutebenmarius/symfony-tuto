<?php

namespace App\Controller;

use App\Entity\CahierTexte;
use App\Entity\Classe;
use App\Entity\Matiere;
use App\Entity\PartieMatiere;
use App\Form\PartieMatiereType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProviseurController extends AbstractController
{
    /**
     * @Route("/proviseur", name="app_proviseur")
     */
    public function index(): Response
    {
        return $this->render('proviseur/index.html.twig', [
            'controller_name' => 'ProviseurController',
        ]);
    }

    /**
     * @Route("/cahier/{id}/partie_matiere", name="consulter_partie_matiere")
     */
    public function cahierPartieMatier(CahierTexte $cahier): Response
    {
        $partieMatieres = $this->getDoctrine()->getRepository(PartieMatiere::class)->findBy(['cahierTexte'=>$cahier]);
        //dd($partieMatieres);
        return $this->render('proviseur/cahier-partie-matiere.html.twig', [
            'partieMatieres' => $partieMatieres,
            'cahier'         => $cahier
        ]);
    }

    /**
     * @Route("/ajouter-partie-matiere/{id}", name="ajouter_partie_matiere")
     */
    public function ajouterPartieMatiere(CahierTexte $cahier,Request $request): Response
    {
        $Partie = new PartieMatiere();
        $Partie->setCahierTexte($cahier);
        $Partie->setDescription("--");
        $Partie->setEstVuParDirecteur(false);
        $form = $this->createForm(PartieMatiereType::class, $Partie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($Partie);
            $manager->flush();
            return $this->redirectToRoute('consulter_partie_matiere',['id' => $cahier->getId()]);
        }
        return $this->render('proviseur/ajouter-pm.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/ajouter-programme", name="ajouter_programme")
     */
   /* public function ajouterProgramme(CahierTexte $cahier,Request $request): Response
    {
        $Partie = new PartieMatiere();
        $Partie->setCahierTexte($cahier);
        $Partie->setDescription("--");
        $Partie->setEstVuParDirecteur(false);
        $form = $this->createForm(PartieMatiereType::class, $Partie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($Partie);
            $manager->flush();
            return $this->redirectToRoute('consulter_partie_matiere',['id' => $cahier->getId()]);
        }
        return $this->render('proviseur/ajouter-pm.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }*/

    /**
     * @Route("/liste-programmes", name="liste_programmes")
     */
   /* public function listeProgrammes(): Response
    {
       
        return $this->render('proviseur/ajouter-pm.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }*/

    /**
     * @Route("/liste/matiere/classe/{id}", name="liste_matiere_par_classe")
     */
    public function listeMatByClasse(Classe $classe){
        $matieres = $this->getDoctrine()
        ->getRepository(Matiere::class)->findBy(['classe' => $classe, 
        'estArchive' => false]);
        return $this->render('proviseur/matieres-classe.html.twig',
    [
        'matieres' => $matieres,
        'classe'   => $classe
    ]);
    }
}

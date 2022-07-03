<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Entity\CahierTexte;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\Section;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArchiverController extends AbstractController
{
    /**
     * @Route("/archiver/niveau/{id}", name="archiver_niveau")
     */
    public function index(Niveau $niveau): Response
    {
        $niveau->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('liste_niveaux');
        return $this->render('archiver/index.html.twig', [
            'controller_name' => 'ArchiverController',
        ]);
    }

    /**
     * @Route("/archiver/section/{id}", name="archiver_section")
     */
    public function archiverSection(Section $section): Response
    {
        $section->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('liste_sections');
        
    }

      /**
     * @Route("/archiver/classe/{id}", name="archiver_classe")
     */
    public function archiverClasse(Classe $classe): Response
    {
        //$classe->setEstArchive(true);
        $classe->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('liste_classes');
        
    }


    /**
     * @Route("/archiver/enseignant/{id}", name="archiver_enseignant")
     */
    public function archiverEnseignant(Utilisateur $utilisateur): Response
    {
        //niveau->setEstArchive(true);
        $utilisateur->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('liste_enseignants');
    }

    /**
     * @Route("/archiver/eleve/{id}", name="archiver_eleve")
     */
    public function archiverEleve(Eleve $eleve): Response
    {
        //$eleve->setEstArchive(true);
        $eleve->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('liste_eleves');
    }

    /**
     * @Route("/archiver/cahier/{id}", name="archiver_cahier")
     */
    public function archiverCahier(CahierTexte $cahier): Response
    {
        //$eleve->setEstArchive(true);
        $cahier->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('app_cahier_texte_index');
    }

    /**
     * @Route("/archiver/affectation/{id}", name="archiver_affectation")
     */
    public function archiverAffectation(Affectation $cahier): Response
    {
        //$eleve->setEstArchive(true);
        $cahier->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('app_affectation_index');
    }

    /**
     * @Route("/archiver/matiere/{id}", name="archiver_matiere")
     */
    public function archiverMatiere(Matiere $matiere){
        //$matiere->setEstArchive(true);
        $matiere->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('app_matiere_index');
    }


}

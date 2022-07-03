<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\RessourcePedagogique;
use App\Entity\Seance;
use App\Form\RessourceType;
use App\Form\SeanceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormateurDController extends AbstractController
{
    /**
     * @Route("/dashboard/formateur", name="app_formateur_d")
     */
    public function index(): Response
    {
        return $this->render('formateur_d/index.html.twig', [
            'controller_name' => 'FormateurDController',
        ]);
    }

    /**
     * @Route("/formations/formateur", name="formations_formateur")
     */
    public function formations(): Response
    {
        $connecte = $this->getUser();
        $formations = $this->getDoctrine()->getRepository(Formation::class)
        ->findBy(['formateur' => $connecte]);
        return $this->render('formateur_d/formations.html.twig', ['formations' => $formations]);
        //dd("formations");
    }

    /**
     * @Route("/seances/formation/{id}", name="seance_formation")
     */
    public function seancesFormation(Formation $formation){
        $seances = $formation->getSeances();
        return $this->render('formateur_d/seances-formation.html.twig', ['seances' => $seances, 'formation' => $formation]);
    }


      /**
     * @Route("/programmer/seance/formation/{id}", name="programmer_une_seance")
     */
    public function programmerSeance(Formation $formation, Request $request){
        $seance = new Seance();
        $seance->setFormation($formation);
        $seance->setEstTermine(false);
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           // dd($seance);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($seance);
            $manager->flush();
            return $this->redirectToRoute('seance_formation', ['id' => $formation->getId()]);
        }
        return $this->render('formateur_d/programmer-seance.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation
        ]);
        //return new Response('under construction');
        //return $this->render('formateur_d/seances-formation.html.twig', ['seances' => $seances, 'formation' => $formation]);
    }


     /**
     * @Route("/demarrer/seance/formation/{id}", name="demarrer_une_seance")
     */
    public function demarrerSeance(Formation $formation, Request $request){
        $seance = new Seance();
        $seance->setFormation($formation);
        $seance->setDateSeance(new \DateTime());
        $seance->setEstTermine(false);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($seance);
        $manager->flush();
        return $this->redirectToRoute('seance_formation',['id' => $formation->getId()]);
    }

    /**
     * @Route("/terminer/seance/{id}", name="terminer_sc")
     */
    public function terminerSeance(Seance $seance){
        $seance->setEstTermine(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('seance_formation',['id' => $seance->getFormation()->getId()]);
    }

    /**
     * @Route("/resources/seance/{id}", name="ressource_seances")
     */
    public function ressources(Seance $seance){
        $ressources = $this->getDoctrine()->getRepository(RessourcePedagogique::class)->findBy(['seance' => $seance]);
        
        return $this->render('formateur_d/ressources.html.twig', [
            'ressources' => $ressources,
            'seance'     => $seance
        ]);
    }

    /**
     * @Route("/ajouter-ressource/{id}", name="ajouter_ressource")
     */
    public function ajouterRessource(Seance $seance, Request $request){
        $r = new RessourcePedagogique();
        $r->setSeance($seance);
        $f = $this->createForm(RessourceType::class, $r);
        $f->handleRequest($request);
        if($f->isSubmitted() && $f->isValid()){
            $fichier = $f->get('fichierUrl')->getData();
            if ($fichier) {
                $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
            
                $newFilename = 'fichier-'.uniqid().'.'.$fichier->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichier->move(
                        $this->getParameter('fichier_seances'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $r->setFichierUrl($newFilename);
            }
            $r->setDateInsertion(new \DateTime());
            $this->getDoctrine()->getManager()->persist($r);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('ressource_seances', ['id' => $seance->getId()]);
        }
        
        return $this->render('formateur_d/ajouter-ressource.html.twig', [
            'form' => $f->createView()
        ]);
    }
}

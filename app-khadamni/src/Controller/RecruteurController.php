<?php

namespace App\Controller;

use App\Entity\Entretien;
use App\Entity\OffreEmploi;
use App\Entity\Postuler;
use App\Entity\Utilisateur;
use App\Form\EntretienType;
use App\Form\OffreEmploiType;
use App\Form\ProfileRecruteurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruteurController extends AbstractController
{
    /**
     * @Route("/recruteur", name="app_recruteur")
     */
    public function index(Request $request): Response
    {
        $profil = $this->getUser();

        $form  = $this->createForm(ProfileRecruteurType::class,$profil);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_recruteur');
        }
        return $this->render('recruteur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recruteur-offres", name="mes_offres")
     */
    public function mesOffres(): Response
    {
        $connecte = $this->getUser();
        $offres = $this->getDoctrine()->getRepository(OffreEmploi::class)->findBy(['recruteur' => $connecte]);
        return $this->render('recruteur/offres.html.twig', [
            'offres' => $offres,
        ]);
    }

    /**
     * @Route("/publier-offre", name="ajouter_offre")
     */
    public function publier(Request $request)
    {
        $connecte = $this->getUser();
        $offre = new OffreEmploi();
        $offre->setEstSupprime(false);
        $offre->setRecruteur($connecte);
        $offre->setAjouterEn(new \DateTime());
        $form  = $this->createForm(OffreEmploiType::class, $offre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($offre);
            $manager->flush();
            return $this->redirectToRoute('mes_offres');
        }
        return $this->render('recruteur/ajouter-offre.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/supprimer-offre/{id}", name="supprimer_offre")
     */
    public function supprimer(OffreEmploi $offre, Request $request)
    {
        $offre->setEstSupprime(true);
        $manager = $this->getDoctrine()->getManager();
        
        $manager->flush();
        return $this->redirectToRoute('mes_offres');
    }

    /**
     * @Route("/detail-offre/{id}", name="detail")
     */
    public function detail(OffreEmploi $offre)
    {

        return $this->render('recruteur/detail-offre.html.twig', ['offre_emploi' => $offre]);
    }

    /**
     * @Route("/modifier-offre/{id}", name="modifier")
     */
    public function modifier(OffreEmploi $offre, Request $request)
    {
        $form  = $this->createForm(OffreEmploiType::class, $offre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($offre);
            $manager->flush();
            return $this->redirectToRoute('mes_offres');
        }
        return $this->render('recruteur/ajouter-offre.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/demandes", name="demandes")
     */
    public function demandes()
    {
        $moi = $this->getUser();
        $demandes  = $this->getDoctrine()->getRepository(Postuler::class)->findBy(['status' => "en cours",'estSupprime' => false]);
        $dmds = [];
        foreach($demandes as $d){
            if($d->getOffre()->getRecruteur() === $moi){
                array_push($dmds, $d);
            }
        }
        return $this->render('recruteur/demandes.html.twig', ['demandes' => $dmds]);
    }

    /**
     * @Route("accepter-demandeur/{id}", name="accepter_demandeur")
     */
    public function accepter(Postuler $postuler){
        $manager = $this->getDoctrine()->getManager();
        $postuler->setStatus("accepte");
        $manager->flush();
        return $this->redirectToRoute('demandes');
    }

    /**
     * @Route("refuser-demandeur/{id}", name="refuser_demandeur")
     */
    public function refuser(Postuler $postuler){
        $manager = $this->getDoctrine()->getManager();
        $postuler->setStatus("refuse");
        $manager->flush();
        return $this->redirectToRoute('demandes');
    }

    /**
     * @Route("liste-entretiens", name="liste_entretiens")
     */
    public function entretiens(){
        $connecte = $this->getUser();
        
        $demandesAcceptes = $this->getDoctrine()->getRepository(Postuler::class)->findBy(['estSupprime' => false, 'status' => 'accepte']);
        $entretiens = [];
        foreach($demandesAcceptes as $demande){
            if($demande->getOffre()->getRecruteur() === $connecte){
                array_push($entretiens, $demande);
            }
           
        }
       return $this->render('recruteur/entretiens.html.twig', [
           'entretiens' => $entretiens
       ]);
    }


    /**
     * @Route("fixer-entretien/{offer_id}/{demandeur_id}", name="fixer_entretien")
     */
    public function fixerEntretien($offer_id, $demandeur_id, Request $request){
        $connecte = $this->getUser();
        $offre = $this->getDoctrine()->getRepository(OffreEmploi::class)->find($offer_id);
        $demandeur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($demandeur_id);
   

        $entretien = new Entretien();
        $entretien->setEstSupprime(false);
        $entretien->setOffre($offre);
        $entretien->setDemandeur($demandeur);
        $form = $this->createForm(EntretienType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){  
            $this->getDoctrine()->getManager()->persist($entretien);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('liste_entretiens');
        }

       return $this->render('recruteur/fixer-entretien.html.twig', [
           'form' => $form->createView()
       ]);
    }
}

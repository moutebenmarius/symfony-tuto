<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Entity\Postuler;
use App\Form\ProfileDemandeurType;
use App\Repository\OffreEmploiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandeurController extends AbstractController
{
    /**
     * @Route("/demandeur", name="app_demandeur")
     */
    public function index(Request $request): Response
    {
        $profil = $this->getUser();

        $form  = $this->createForm(ProfileDemandeurType::class,$profil);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_recruteur');
        }
        return $this->render('demandeur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/liste-offres", name="liste_offres", methods={"GET"})
     */
    public function liste(OffreEmploiRepository $offreEmploiRepository): Response
    {
        return $this->render('demandeur/liste-offres.html.twig', [
            'offre_emplois' => $offreEmploiRepository->findAll(),
        ]);
    }

     /**
     * @Route("/postuler/{id}", name="postuler", methods={"GET"})
     */
    public function posulter(OffreEmploi $offreEmploi): Response
    {
        $moi = $this->getUser();
        $postuler = new Postuler();
        $postuler->setEstSupprime(false);
        $postuler->setDemandeur($moi);
        $postuler->setOffre($offreEmploi);
        $postuler->setDatePostule(new \DateTime());
        $postuler->setStatus("en cours");
        $this->getDoctrine()->getManager()->persist($postuler);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('mes_postules');
    }

     /**
     * @Route("/annuler/{id}", name="annuler", methods={"GET"})
     */
    public function annuler(Postuler $postule): Response
    {
        $postule->setEstSupprime(true);
        $postule->setStatus("annule");
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('mes_postules');
    }

    /**
     * @Route("/mes-postules", name="mes_postules", methods={"GET"})
     */
    public function mesPostules(): Response
    {
        $moi = $this->getUser();
        $mesPostules = $this->getDoctrine()->getRepository(Postuler::class)->findBy(['demandeur'=>$moi,'estSupprime' => false]);
        return $this->render('demandeur/mes-postules.html.twig', ['postules' => $mesPostules]);
    }

    
}

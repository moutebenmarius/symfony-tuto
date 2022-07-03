<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\OffreEmploiRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(UtilisateurRepository $repo, OffreEmploiRepository $offreRepo): Response
    {
        return $this->render('admin/index.html.twig', [
            'nbr_offres' => count($offreRepo->findAll()),
            'nbr_recruteurs' => count($repo->findBy(['role'=>'ROLE_RECRUTEUR'])),
            'nbr_demandeurs' => count($repo->findBy(['role'=>'ROLE_DEMANDEUR'])),
            'nbr_inscrits' => count($repo->findAll()),
        ]);
    }

    /**
     * @Route("/activer/{user}", name="activer")
     */
    public function activer(Request $request,Utilisateur $user){
        $user->setStatus("est_active");
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
        $type = $user->getRole() == "ROLE_DEMANDEUR" ? "Demandeur d'emploi":"Récruteur";
        $redirect = $user->getRole() == "ROLE_DEMANDEUR" ? "app_demandeurs_index":"app_recruteurs_index";;
        $this->addFlash('info', "Le $type a été confirmé");
        return $this->redirectToRoute($redirect);
    }
    /**
     * @Route("/desactiver/{user}", name="desactiver")
     */
    public function desactiver(Request $request,Utilisateur $user){
        $user->setStatus("est_desactive");
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
        $type = $user->getRole() == "ROLE_DEMANDEUR" ? "Demandeur d'emploi":"Récruteur";
        $redirect = $user->getRole() == "ROLE_DEMANDEUR" ? "app_demandeurs_index":"app_recruteurs_index";;
        $this->addFlash('info', "Le $type a été refusé");
        return $this->redirectToRoute($redirect);
    }
}

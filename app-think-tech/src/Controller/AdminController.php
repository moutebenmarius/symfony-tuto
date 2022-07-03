<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/supprimer/formateur/{id}", name="supprimer_formateur")
     *
     * @return void
     */
    public function supprimerFormateur(Utilisateur $formateur, EntityManagerInterface $manager){
        $manager->remove($formateur);
        $manager->flush();
        return $this->redirectToRoute('app_formateur_index');
    }


    /**
     * @Route("/supprimer/apprenant/{id}", name="supprimer_apprenant")
     * @return void
     */
    public function supprimerApprenant(Utilisateur $formateur, EntityManagerInterface $manager){
        $manager->remove($formateur);
        $manager->flush();
        return $this->redirectToRoute('app_apprenant_index');
    }

     /**
     * @Route("/enseignant/chercher", name="chercher_enseignant")
     */
    public function chercherEnseignant(HttpFoundationRequest $request, UtilisateurRepository $enseignantRepo): Response
    {
        $q  = $request->get('q');
        $enseignants = $enseignantRepo->getAllFormateursByKeyword($q);
        return $this->render('formateur/index.html.twig', [
            'enseignants' => $enseignants,
            'retour'      => true
        ]);
        /*return $this->render('admin/liste-cahiers.html.twig', [
            'controller_name' => 'AdminController',
        ]);*/
    }

    /**
     * @Route("/apprenant/chercher", name="chercher_apprenant")
     */
    public function chercherapprenant(HttpFoundationRequest $request, UtilisateurRepository $enseignantRepo): Response
    {
        $q  = $request->get('q');
        $enseignants = $enseignantRepo->getAllApprenantsByKeyword($q);
        return $this->render('apprenant/index.html.twig', [
            'apprenants' => $enseignants,
            'retour'      => true
        ]);
        /*return $this->render('admin/liste-cahiers.html.twig', [
            'controller_name' => 'AdminController',
        ]);*/
    }

    /**
     * @Route("/gerer-commandes", name="gerer_commandes")
     */
    public function gererCommandes(){
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        return $this->render('admin/gestion-commandes.html.twig', [
            'commandes' => $commandes
        ]);
    }

    /**
     * @Route("/detail-comot_de_passe_oubliemmande/{id}", name="detail_commande")
     */
    public function detail(Commande $commande){
        return $this->render('admin/detail.html.twig', [
            'commande' => $commande
        ]);
    }


    
}

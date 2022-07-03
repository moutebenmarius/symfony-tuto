<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/gestion-medecins", name="admin_gestion_medecins")
     */
    public function gestionMedecins(): Response
    {
        // call the doctrine
        $doctrine = $this->getDoctrine();
        // call the repo
        $repo = $doctrine->getRepository(Utilisateur::class);

        // get the doctors
        $doctors = $repo->findBy(['role' => 'ROLE_MEDECIN']);
        return $this->render('admin/medecins.html.twig', [
            'doctors' => $doctors,
        ]);
    }

    /**
     * @Route("/gestion-laboratoires", name="admin_gestion_laboratoires")
     */
    public function gestionLaboratoires(): Response
    {
        // call the doctrine
        $doctrine = $this->getDoctrine();
        // call the repo
        $repo = $doctrine->getRepository(Utilisateur::class);

        // get the doctors
        $laboratoires = $repo->findBy(['role' => 'ROLE_LABORATOIRE']);
        return $this->render('admin/laboratoires.html.twig', [
            'laboratoires' => $laboratoires,
        ]);
    }

    /**
     * @Route("/gestion-pharmacies", name="admin_gestion_pharmacies")+
     */
    public function gestionPharmacies(): Response
    {
        // call the doctrine
        $doctrine = $this->getDoctrine();
        // call the repo
        $repo = $doctrine->getRepository(Utilisateur::class);

        // get the doctors
        $pharmacies = $repo->findBy(['role' => 'ROLE_PHARMACIE']);
        return $this->render('admin/pharmacies.html.twig', [
            'pharmacies' => $pharmacies,
        ]);
    }


    /**
     * @Route("/gestion-imageries", name="admin_gestion_imageries")+
     */
    public function gestionImagerieMedicale(): Response
    {
        // call the doctrine
        $doctrine = $this->getDoctrine();
        // call the repo
        $repo = $doctrine->getRepository(Utilisateur::class);

        // get the doctors
        $imagerie_medicales = $repo->findBy(['role' => 'ROLE_IMAGERIE_MEDICALE']);
        return $this->render('admin/imagerie_medicale.html.twig', [
            'imageries' => $imagerie_medicales,
        ]);
    }
}

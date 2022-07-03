<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Form\MedicamentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PharmacieController extends AbstractController
{
    /**
     * @Route("/pharmacie", name="app_pharmacie")
     */
    public function index(): Response
    {
        return $this->render('pharmacie/index.html.twig', [
            'controller_name' => 'PharmacieController',
        ]);
    }

     /**
     * @Route("/liste-medicaments", name="mes_medicaments")
     */
    public function mesMedicaments(): Response
    {
        $pharmacieConnecte = $this->getUser();
        $medicaments = $this->getDoctrine()->getRepository(Medicament::class)->findBy(['pharmacie' => $pharmacieConnecte]);
        return $this->render('pharmacie/liste-medicaments.html.twig', [
            'medicaments' => $medicaments
        ]);
    }

    /**
     * @Route("/ajouter-medicament", name="ajouter_medicament")
     */
    public function ajouterMedicament(Request $request): Response
    {
        $pharmacieConnecte = $this->getUser();
        $medicament = new Medicament();
        $medicament->setEstExiste(true);
        $medicament->setPharmacie($pharmacieConnecte);
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($medicament);
            $manager->flush();
            return $this->redirectToRoute('mes_medicaments');
        }
        return $this->render('pharmacie/ajouter-medicament.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/est-disponible/{id}", name="est_disponible")
     */
    public function estDisponible(Medicament $medicament): Response
    {
        $medicament->setEstExiste(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('mes_medicaments');
    }

     /**
     * @Route("/nestpas-disponible/{id}", name="set_pas_diponible")
     */
    public function NestDisponible(Medicament $medicament): Response
    {
            $medicament->setEstExiste(false);
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->redirectToRoute('mes_medicaments');
       
    }
}

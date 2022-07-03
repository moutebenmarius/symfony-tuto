<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KhadamniController extends AbstractController
{
    /**
     * @Route("/", name="app_khadamni")
     */
    public function index(): Response
    {
        $offres = $this->getDoctrine()->getRepository(OffreEmploi::class)->findAll();
        return $this->render('khadamni/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    /**
     * @Route("/offres", name="offres")
     */
    public function offres(): Response
    {
        $offres = $this->getDoctrine()->getRepository(OffreEmploi::class)->findAll();
        return $this->render('khadamni/offres.html.twig', [
            'offres' => $offres,
        ]);
    }

    /**
     * @Route("/detail-offre", name="detail_offre")
     */
    public function detailOffre(): Response
    {
        return $this->render('khadamni/offres.html.twig', [
            'controller_name' => 'KhadamniController',
        ]);
    }

    /**
     * @Route("/a-propos", name="about")
     */
    public function about(): Response
    {
        return $this->render('khadamni/about.html.twig', [
            'controller_name' => 'KhadamniController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('khadamni/contact.html.twig', [
            'controller_name' => 'KhadamniController',
        ]);
    }
}

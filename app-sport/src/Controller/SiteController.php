<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="app_site")
     */
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }


    /**
     * @Route("/bonjour", name="dire_bonjour")
     */
    public function bonjour(){
        return $this->render('bonjour.html.twig');
    }

      /**
     * @Route("/merci", name="merci")
     */
    public function merci(){
        return new Response("<h1>Ahla bik</h1>");
    }
}

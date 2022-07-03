<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CentreImagerieMedicaleController extends AbstractController
{
    /**
     * @Route("/centre/imagerie/medicale", name="app_centre_imagerie_medicale")
     */
    public function index(): Response
    {
        return $this->render('centre_imagerie_medicale/index.html.twig', [
            'controller_name' => 'CentreImagerieMedicaleController',
        ]);
    }
}

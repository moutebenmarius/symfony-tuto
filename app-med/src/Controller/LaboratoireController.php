<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LaboratoireController extends AbstractController
{
    /**
     * @Route("/laboratoire", name="app_laboratoire")
     */
    public function index(): Response
    {
        return $this->render('laboratoire/index.html.twig', [
            'controller_name' => 'LaboratoireController',
        ]);
    }
}

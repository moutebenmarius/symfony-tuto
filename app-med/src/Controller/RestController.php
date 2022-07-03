<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RestController extends AbstractController
{
    /**
     * @Route("/api/get-rendez-vous", name="app_rest")
     */
    public function index(): Response
    {
        $utilisateurs = [
            ['id' => 1 , 'nom' => 'foulen', 'age' => 12],
            ['id' => 3 , 'nom' => 'b foulen', 'age' => 22],
            ['id' => 9 , 'nom' => 'ahmed', 'age' => 50],
            ['id' => 10 , 'nom' => 'ranya', 'age' => 20],
            ['id' => 12 , 'nom' => 'zayneb', 'age' => 20],
        ];
        

        //$rendezvous = $this->getDoctrine()->getRepository(RendezVous::class)->findAll();

        return $this->json(["medecins" => $utilisateurs]);
    }
}

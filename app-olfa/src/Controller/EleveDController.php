<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Entity\Eleve;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EleveDController extends AbstractController
{
    /**
     * @Route("/evaluation-enseignant", name="app_eleve_d")
     */
    public function index(): Response
    {
        $eleve = $this->getUser();
        $cinParent = $eleve->getCin();
        $infoEleve = $this->getDoctrine()->getRepository(Eleve::class)->findOneBy(['cinParent' => $cinParent]);
        $classEleve = $infoEleve->getClasse();
        $enseignantClasse = $this->getDoctrine()->getRepository(Affectation::class)->findBy(['classe' => $classEleve]);
        dd($enseignantClasse);
        dd($classEleve);
        dd($infoEleve);
        dd($eleve);
        return $this->render('eleve_d/index.html.twig', [
            'controller_name' => 'EleveDController',
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\ApprenantFormation;
use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApprenantDController extends AbstractController
{
    /**
     * @Route("/dashboard/apprenant", name="app_apprenant_d")
     */
    public function index(): Response
    {
        return $this->render('apprenant_d/index.html.twig', [
            'controller_name' => 'ApprenantDController',
        ]);
    }


     /**
     * @Route("/catalogue-formations", name="catalogue_formation")
     */
    public function catalogueFormaion(): Response
    {
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        return $this->render('apprenant_d/catalogue-formations.html.twig', ['formations' => $formations]);
    }
     /**
     * @Route("/mes-formations", name="formations_apprenants")
     */
    public function formations(): Response
    {
        $connecte = $this->getUser();
        $formations = $this->getDoctrine()->getRepository(ApprenantFormation::class)->findBy(['apprenant' => $connecte]);
        return $this->render('apprenant_d/mes-formations.html.twig', ['formations' => $formations]);
    }

    /**
     * @Route("/inscrire/formation/{id}", name="inscrire_formation")
     *
     */
    public function inscrire(Formation $formation){
        $connecte = $this->getUser();
        $existe = $this->getDoctrine()->getRepository(ApprenantFormation::class)->findOneBy(['apprenant' => $connecte, 'formation'=>$formation]);
        if($existe){
            $this->addFlash("warning", "vous êtes déjà inscrit");
            return $this->redirectToRoute('formations_apprenants');
        }
        $apprenantFormation = new ApprenantFormation();
        $apprenantFormation->setFormation($formation);
        $apprenantFormation->setApprenant($connecte);
        $apprenantFormation->setDateInscription(new \DateTime());
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($apprenantFormation);
        $manager->flush();
        $this->addFlash("success", "vous êtes inscrit avec succès");
        return $this->redirectToRoute('formations_apprenants');

    }

    /**
     * @Route("/ressource-formation/{id}", name="resource_formation")
     *
     * @param Formation $formation
     * @return void
     */
    public function rp(Formation $formation){
        $seances = $formation->getSeances();
        $rs = [];
        foreach($seances as $s){
            array_push($rs, ['seance' => $s, 'fichiers' => $s->getRessourcePedagogiques()]);
        }
        return $this->render('apprenant_d/ressources-formation.html.twig', ['ressources' => $rs]);
    }
}

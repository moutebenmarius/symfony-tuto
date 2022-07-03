<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Reservation;
use App\Entity\Seance;
use App\Entity\Tournoi;
use App\Form\ProfileType;
use App\Form\SeanceType;
use App\Form\TournoiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CoachController extends AbstractController
{
    /**
     * @Route("/coach", name="app_coach")
     */
    public function index(): Response
    {
        $actualites = $this->getDoctrine()->getRepository(Actualite::class)->findAll();
        return $this->render('coach/index.html.twig', [
            'actualites' => $actualites,
        ]);
    }

    /**
     * @Route("/gerer-seances", name="gerer_seances")
     */
    public function gerer_seances(): Response
    {
        $coach = $this->getUser();
        $seances = $this->getDoctrine()->getRepository(Seance::class)->findBy(['coach' => $coach]);
        return $this->render('coach/gerer-seances.html.twig', [
            'seances' => $seances,
        ]);
    }

    /**
     * @Route("/gerer-reservations", name="gerer_reservations")
     */
    public function gerer_reservations(): Response
    {
        $coach = $this->getUser();
        $reservations = [];
        $rsvs = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        foreach($rsvs as $r){
            if($r->getSeance()->getCoach() === $coach){
                array_push($reservations, $r);
            }
        }
        return $this->render('coach/gerer-reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    /**
     * @Route("/planifier-seance", name="planifier_seance")
     */
    public function planifier_seance(Request $request): Response
    {
        $seance = new Seance();
        $seance->setStatus('incompleted');
        $coach = $this->getUser();
        $coachType = $coach->getTypeSport()->getLibelle();
        $seance->setTypeSeance($coachType);
        $seance->setCoach($coach);
        $form   = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($seance);
            $manager->flush();
            $this->addFlash('info', "votre seance a été enregistreé");
            return $this->redirectToRoute('gerer_seances');
        }

        return $this->render('coach/planifier-seance.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/planifier-tournoi", name="planifier_tournoi")
     */
    public function planifier_tournoi(Request $request): Response
    {
        $tournoi =  new Tournoi();
        $tournoi->setVainqueur("--");
        $tournoi->setScore("--");
        $tournoi->setArbitre($this->getUser());
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($tournoi);
            $manager->flush();
            $this->addFlash("info", "Tournoi enregistré");
            return $this->redirectToRoute('gerer_tournoi');
        }
        return $this->render('coach/planifier-tournoi.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annuler-seance/{id}", name="annuler_seance")
     */
    public function annuler_seance(Seance $seance): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($seance);
        $manager->flush();
        $this->addFlash('success', "l'annulation de votre séance est faite avec succées");
        return $this->redirectToRoute('gerer_seances');
    }

    /**
     * @Route("/gerer-tournoi", name="gerer_tournoi")
     */
    public function gerer_tournoi(): Response
    {
        $coach = $this->getUser();
        $tournois = $this->getDoctrine()->getRepository(Tournoi::class)->findBy(['arbitre' => $coach]);
        return $this->render('coach/gerer-tournois.html.twig', [
            'tournois' => $tournois,
        ]);
    }

    /**
     * @Route("/mon_profil_coach", name="mon_profil_coach")
     */
    public function mon_profil(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $admin = $this->getUser();
        $form = $this->createForm(ProfileType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($admin, $admin->getPassword());
            $admin->setPassword($hash);
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            $this->addFlash('success', "Votre profil est mis à jour");
            return $this->redirectToRoute('actualite');
        }
        return $this->render('coach/profil.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/accepter_reservation/{id}", name="accepter_reservation")
     */
    public function accepter_reservation(Reservation $reservation): Response
    {
      
        $reservation->setStatut("accepte");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('gerer_reservations');
      
    }

     /**
     * @Route("/annuler_reservation/{id}", name="annuler_reservation")
     */
    public function annuler_reservation(Reservation $reservation): Response
    {
        $reservation->setStatut("refuser");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('gerer_reservations');
    }
}

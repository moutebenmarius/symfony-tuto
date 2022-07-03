<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Entity\Actualite;
use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdherentController extends AbstractController
{
    /**
     * @Route("/adherent", name="app_adherent")
     */
    public function index(): Response
    {
        $actualites = $this->getDoctrine()->getRepository(Actualite::class)->findAll();
        return $this->render('adherent/index.html.twig', ['actualites' => $actualites]);
    }


    /**
     * @Route("/liste-seances", name="liste_seances")
     */
    public function gerer_seances(): Response
    {
        $coach = $this->getUser();
        $seances = $this->getDoctrine()->getRepository(Seance::class)->findAll();
        return $this->render('adherent/liste-seances.html.twig', [
            'seances' => $seances,
        ]);
    }

     /**
     * @Route("/reserver-seance/{id}", name="reserver_seance")
     */
    public function reserver_seance(Seance $seance): Response
    {
        $connecte = $this->getUser();
        $reservation = new Reservation();
        $reservation->setSeance($seance);
        $reservation->setAdherent($connecte);
        $reservation->setDateReservation(new \Datetime());
        $reservation->setStatut("en cours");
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($reservation);
        $manager->flush();
        return $this->redirectToRoute('mes_reservations');
        /*$coach = $this->getUser();
        $seances = $this->getDoctrine()->getRepository(Seance::class)->findAll();
        return $this->render('adherent/liste-seances.html.twig', [
            'seances' => $seances,
        ]);*/
    }

    /**
     * @Route("/mes-reservations/", name="mes_reservations")
     */
    public function mes_reservations(): Response
    {
        $connecte = $this->getUser();
        $reservations = $connecte->getReservations();
        return $this->render('adherent/mes-reservations.html.twig', [
            'reservations' => $reservations
        ]);
    }

    /**
     * @Route("/annuler-reservation/{id}", name="annuler_reservation")
     */
    public function annuler(Reservation $reservation){
        dd($reservation);
        $reservation->setStatut("annule");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('mes_reservations'); 
    }
}

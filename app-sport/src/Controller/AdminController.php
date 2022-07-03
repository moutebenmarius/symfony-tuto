<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Tournoi;
use App\Entity\Utilisateur;
use App\Form\ActualiteType;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{

     /**
     * @Route("/aprouver-adherent/{id}", name="approuver_adherent")
     */
    public function approuver_adherent(Utilisateur $user): Response
    {
        $user->setStatus("confirme");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        $this->addFlash("success", $user->getNom().' '.$user->getPrenom()." est approuvé");
        return $this->redirectToRoute('gestion_adherents');
    }

    /**
     * @Route("/bloquer-adherent/{id}", name="bloquer_adherent")
     */
    public function bloquer_adherent(Utilisateur $user): Response
    {
        $user->setStatus("bloque");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        $this->addFlash("success", $user->getNom().' '.$user->getPrenom()." est approuvé");
        return $this->redirectToRoute('gestion_adherents');
    }

    /**
     * @Route("/bloquer-coach/{id}", name="bloquer_coach")
     */
    public function bloquer_coach(Utilisateur $user): Response
    {
        $user->setStatus("bloque");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        $this->addFlash("success", $user->getNom().' '.$user->getPrenom()." est bloque");
        return $this->redirectToRoute('gestion_coach');
    }

     /**
     * @Route("/aprouver-coach/{id}", name="approuver_coach")
     */
    public function approuver_coach(Utilisateur $user): Response
    {
        $user->setStatus("confirme");
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        $this->addFlash("success", $user->getNom().' '.$user->getPrenom()." est approuvé");
        return $this->redirectToRoute('gestion_coach');
    }
    

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        $adherents = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(['role' => 'ROLE_ADHERENT' ]);
        $coachs = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(['role' => 'ROLE_COACH' ]);
        $tournois = $this->getDoctrine()->getRepository(Tournoi::class)->findAll();
        return $this->render('admin/index.html.twig', [
            'adherents' => count($adherents),
            'coachs' => count($coachs),
            'tournois' => count($tournois),
        ]);
    }

   /**
     * @Route("/gerer_actualite", name="actualite")
     */
    public function gerer_actualite(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $actualites = $this->getDoctrine()->getRepository(Actualite::class)->findAll();
        return $this->render('admin/actualite.html.twig', ['actualites' => $actualites]);
    }

    /**
     * @Route("/gerer-coach", name="gestion_coach")
     */
    public function gestion_coach(): Response
    {
        $coachs = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(['role' => 'ROLE_COACH']);
        return $this->render('admin/gestion-coach.html.twig', [
            'coachs' => $coachs,
        ]);
    }

    /**
     * @Route("/gerer-adherents", name="gestion_adherents")
     */
    public function gestion_adherents(): Response
    {
        $coachs = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(['role' => 'ROLE_ADHERENT']);
        return $this->render('admin/gestion-adherents.html.twig', [
            'coachs' => $coachs,
        ]);
    }

    /**
     * @Route("/consulter_tournois", name="consulter_tournois")
     */
    public function consulter_tournois(): Response
    {
        $tournois = $this->getDoctrine()->getRepository(Tournoi::class)->findAll();
        return $this->render('admin/consulter-tournois.html.twig', [
            'tournois' => $tournois,
        ]);
    }

    /**
     * @Route("/mon_profil", name="mon_profil")
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
            //$this->addFlash('success', "Votre profil est mis à jour");
            return $this->redirectToRoute('actualite');
        }
        return $this->render('admin/profil.html.twig', ['form' => $form->createView()]);
    }

     

     /**
     * @Route("/ajouter_actualite", name="ajouter_actualite")
     */
    public function ajouter_actualite(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
       $actualite = new Actualite();
       $actualite->setCreatedAt(new \DateTime());
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
           
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($actualite);
            $manager->flush();
            //$this->addFlash('success', "la publication a été mis en actualité");
            return $this->redirectToRoute('actualite');
        }
        return $this->render('admin/ajouter-publication.html.twig', ['form' => $form->createView()]);
    }
     /**
     * @Route("/supprimer_publication/{id}", name="supprimer_publication")
     */
    public function supprimer_publication(Actualite $publication): Response
    {
     
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($publication);
            $manager->flush();
            $this->addFlash('success', "la publication a été supprimée");
            return $this->redirectToRoute('actualite');
    }
}

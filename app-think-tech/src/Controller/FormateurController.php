<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RechercheType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/formateur")
 */
class FormateurController extends AbstractController
{
    /**
     * @Route("/", name="app_formateur_index", methods={"GET","POST"})
     */
    public function index(UtilisateurRepository $utilisateurRepository, Request $request): Response
    {
        $formRecherche = $this->createForm(RechercheType::class,null);
        $formRecherche->handleRequest($request);
        if($formRecherche->isSubmitted() && $formRecherche->isValid()){
            $keyword = $formRecherche->get('keyword')->getData();
            return $this->render('formateur/index.html.twig', [
                'enseignants' => $utilisateurRepository->getAllFormateursByKeyword($keyword),
                'formulaire'   => $formRecherche->createView()
            ]);
        }
        return $this->render('formateur/index.html.twig', [
            'enseignants' => $utilisateurRepository->findBy(['role'=>"ROLE_FORMATEUR"]),
            'formulaire'   => $formRecherche->createView(),
            'retour'      => false
        ]);
    }

    /**
     * @Route("/new", name="app_formateur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UtilisateurRepository $utilisateurRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setRole("ROLE_FORMATEUR");
        $utilisateur->setPhoto("standard.png");
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $cin = $utilisateur->getCin();
           $hash = $encoder->encodePassword($utilisateur, $cin);
           $utilisateur->setPassword($hash);
            $this->addFlash('info',"le formateur a été ajouté");
            $utilisateurRepository->add($utilisateur);
            return $this->redirectToRoute('app_formateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_formateur_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('formateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_formateur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->add($utilisateur);
            $this->addFlash('info', "le formateur est modifié avec succée");
            return $this->redirectToRoute('app_formateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_formateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur);
        }

        return $this->redirectToRoute('app_formateur_index', [], Response::HTTP_SEE_OTHER);
    }
}

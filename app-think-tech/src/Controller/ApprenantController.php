<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RechercheType;
use App\Form\Utilisateur1Type;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/apprenant")
 */
class ApprenantController extends AbstractController
{
    /**
     * @Route("/", name="app_apprenant_index", methods={"GET", "POST"})
     */
    public function index(UtilisateurRepository $utilisateurRepository, Request $request): Response
    {
        $formRecherche = $this->createForm(RechercheType::class,null);
        $formRecherche->handleRequest($request);
        if($formRecherche->isSubmitted() && $formRecherche->isValid()){
            $keyword = $formRecherche->get('keyword')->getData();
            return $this->render('apprenant/index.html.twig', [
                'utilisateurs' => $utilisateurRepository->getAllApprenantsByKeyword($keyword),
                'formulaire'   => $formRecherche->createView()
            ]);
        }
        return $this->render('apprenant/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findBy(['role'=>'ROLE_APPRENANT']),
            'formulaire'   => $formRecherche->createView(),
            'retour'       => false
        ]);
    }

    /**
     * @Route("/new", name="app_apprenant_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(Utilisateur1Type::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->add($utilisateur);
            return $this->redirectToRoute('app_apprenant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('apprenant/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_apprenant_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('apprenant/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_apprenant_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(Utilisateur1Type::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->add($utilisateur);
            return $this->redirectToRoute('app_apprenant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('apprenant/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_apprenant_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur);
        }

        return $this->redirectToRoute('app_apprenant_index', [], Response::HTTP_SEE_OTHER);
    }
}

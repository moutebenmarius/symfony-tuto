<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Form\Niveau1Type;
use App\Repository\NiveauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/niveau")
 */
class NiveauController extends AbstractController
{
    /**
     * @Route("/", name="app_niveau_index", methods={"GET"})
     */
    public function index(NiveauRepository $niveauRepository): Response
    {
        return $this->render('niveau/index.html.twig', [
            'niveaux' => $niveauRepository->findBy(['estArchive' => false]),
        ]);
    }

    /**
     * @Route("/new", name="app_niveau_new", methods={"GET", "POST"})
     */
    public function new(Request $request, NiveauRepository $niveauRepository): Response
    {
        $niveau = new Niveau();
        $niveau->setEstArchive(false);
        $form = $this->createForm(Niveau1Type::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $niveauRepository->add($niveau);
            return $this->redirectToRoute('app_niveau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('niveau/new.html.twig', [
            'niveau' => $niveau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_niveau_show", methods={"GET"})
     */
    public function show(Niveau $niveau): Response
    {
        return $this->render('niveau/show.html.twig', [
            'niveau' => $niveau,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_niveau_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Niveau $niveau, NiveauRepository $niveauRepository): Response
    {
        $form = $this->createForm(Niveau1Type::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $niveauRepository->add($niveau);
            return $this->redirectToRoute('liste_niveaux', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/modifier-niveau.html.twig', [
            'niveau' => $niveau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_niveau_delete", methods={"POST"})
     */
    public function delete(Request $request, Niveau $niveau, NiveauRepository $niveauRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$niveau->getId(), $request->request->get('_token'))) {
            $niveauRepository->remove($niveau);
        }

        return $this->redirectToRoute('app_niveau_index', [], Response::HTTP_SEE_OTHER);
    }
}

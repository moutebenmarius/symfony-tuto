<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Form\AffectationType;
use App\Repository\AffectationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/affectation")
 */
class AffectationController extends AbstractController
{
    /**
     * @Route("/", name="app_affectation_index", methods={"GET"})
     */
    public function index(AffectationRepository $affectationRepository): Response
    {
        return $this->render('affectation/index.html.twig', [
            'affectations' => $affectationRepository->findBy(['estArchive'=>false]),
        ]);
    }

    /**
     * @Route("/new", name="app_affectation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AffectationRepository $affectationRepository): Response
    {
        $affectation = new Affectation();
        $affectation->setEstArchive(false);
        $form = $this->createForm(AffectationType::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classe = $affectation->getClasse();
            $matiere = $affectation->getMatiere();
            $affectationExiste = $this->getDoctrine()->getRepository(Affectation::class)->findOneBy(['classe' => $classe, 'matiere' => $matiere]);

           if($affectationExiste){
               $this->addFlash("warning","impossible de reaffecter la matiere dans une même classe");
               return $this->redirectToRoute('app_affectation_index');
           }
            $affectationRepository->add($affectation);
            return $this->redirectToRoute('app_affectation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectation/new.html.twig', [
            'affectation' => $affectation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_affectation_show", methods={"GET"})
     */
    public function show(Affectation $affectation): Response
    {
        return $this->render('affectation/show.html.twig', [
            'affectation' => $affectation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_affectation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Affectation $affectation, AffectationRepository $affectationRepository): Response
    {
        $form = $this->createForm(AffectationType::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affectationRepository->add($affectation);
            return $this->redirectToRoute('app_affectation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectation/edit.html.twig', [
            'affectation' => $affectation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_affectation_delete", methods={"POST"})
     */
    public function delete(Request $request, Affectation $affectation, AffectationRepository $affectationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$affectation->getId(), $request->request->get('_token'))) {
            $affectationRepository->remove($affectation);
        }

        return $this->redirectToRoute('app_affectation_index', [], Response::HTTP_SEE_OTHER);
    }
}

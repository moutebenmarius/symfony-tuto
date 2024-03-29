<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/matiere")
 */
class MatiereController extends AbstractController
{
    /**
     * @Route("/", name="app_matiere_index", methods={"GET"})
     */
    public function index(MatiereRepository $matiereRepository): Response
    {
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matiereRepository->findBy(['estArchive'=>false]),
        ]);
    }

    /**
     * @Route("/new", name="app_matiere_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MatiereRepository $matiereRepository): Response
    {
        $matiere = new Matiere();
        $matiere->setEstArchive(false);
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matiereExiste = $this->getDoctrine()->getRepository(Matiere::class)
                            ->findOneBy(['classe' => $matiere->getClasse(), 'libelle' => $matiere->getLibelle()]);
            if($matiereExiste){
                $this->addFlash("warning","La matière est déjà existe par cet classe");
                return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
            }
            $matiereRepository->add($matiere);
            return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matiere/new.html.twig', [
            'matiere' => $matiere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_matiere_show", methods={"GET"})
     */
    public function show(Matiere $matiere): Response
    {
        return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_matiere_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Matiere $matiere, MatiereRepository $matiereRepository): Response
    {
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matiereRepository->add($matiere);
            return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matiere/edit.html.twig', [
            'matiere' => $matiere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_matiere_delete", methods={"POST"})
     */
    public function delete(Request $request, Matiere $matiere, MatiereRepository $matiereRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matiere->getId(), $request->request->get('_token'))) {
            $matiereRepository->remove($matiere);
        }

        return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
    }
}

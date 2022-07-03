<?php

namespace App\Controller;

use App\Entity\CahierTexte;
use App\Form\CahierTexteType;
use App\Repository\CahierTexteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cahier/texte")
 */
class CahierTexteController extends AbstractController
{
    /**
     * @Route("/", name="app_cahier_texte_index", methods={"GET"})
     */
    public function index(CahierTexteRepository $cahierTexteRepository): Response
    {
        return $this->render('cahier_texte/index.html.twig', [
            'cahier_textes' => $cahierTexteRepository->findBy(['estArchive'=>false]),
        ]);
    }

    /**
     * @Route("/new", name="app_cahier_texte_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CahierTexteRepository $cahierTexteRepository): Response
    {
        $cahierTexte = new CahierTexte();
        $cahierTexte->setEstArchive(false);
        $cahierTexte->setLibelle(uniqid());
        $form = $this->createForm(CahierTexteType::class, $cahierTexte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existe = $cahierTexteRepository->findOneBy(['classe'=>$cahierTexte->getClasse()]);
            if($existe){
                $this->addFlash("info","Vous avez déjà affecter cette classe a un cahier");
                return $this->redirectToRoute('app_cahier_texte_new', [], Response::HTTP_SEE_OTHER);
            }
            $cahierTexteRepository->add($cahierTexte);
            return $this->redirectToRoute('app_cahier_texte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cahier_texte/new.html.twig', [
            'cahier_texte' => $cahierTexte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_cahier_texte_show", methods={"GET"})
     */
    public function show(CahierTexte $cahierTexte): Response
    {
        return $this->render('cahier_texte/show.html.twig', [
            'cahier_texte' => $cahierTexte,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_cahier_texte_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CahierTexte $cahierTexte, CahierTexteRepository $cahierTexteRepository): Response
    {
        $form = $this->createForm(CahierTexteType::class, $cahierTexte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cahierTexteRepository->add($cahierTexte);
            return $this->redirectToRoute('app_cahier_texte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cahier_texte/edit.html.twig', [
            'cahier_texte' => $cahierTexte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_cahier_texte_delete", methods={"POST"})
     */
    public function delete(Request $request, CahierTexte $cahierTexte, CahierTexteRepository $cahierTexteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cahierTexte->getId(), $request->request->get('_token'))) {
            $cahierTexteRepository->remove($cahierTexte);
        }

        return $this->redirectToRoute('app_cahier_texte_index', [], Response::HTTP_SEE_OTHER);
    }
}

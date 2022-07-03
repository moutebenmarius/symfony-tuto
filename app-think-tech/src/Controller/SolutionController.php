<?php

namespace App\Controller;

use App\Entity\Solution;
use App\Form\SolutionType;
use App\Repository\SolutionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/solution")
 */
class SolutionController extends AbstractController
{
    /**
     * @Route("/", name="app_solution_index", methods={"GET"})
     */
    public function index(SolutionRepository $solutionRepository): Response
    {
        return $this->render('solution/index.html.twig', [
            'solutions' => $solutionRepository->findBy(['estArchive' => false]),
        ]);
    }

    /**
     * @Route("/new", name="app_solution_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SolutionRepository $solutionRepository): Response
    {
        $solution = new Solution();
        $solution->setEstArchive(false);
        $form = $this->createForm(SolutionType::class, $solution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $newFilename = 'solution-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('solution_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $solution->setPhoto($newFilename);
           
            }
            $solutionRepository->add($solution);
            $this->addFlash('info','Solution a été ajouté');
            return $this->redirectToRoute('app_solution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('solution/new.html.twig', [
            'solution' => $solution,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_solution_show", methods={"GET"})
     */
    public function show(Solution $solution): Response
    {
        return $this->render('solution/show.html.twig', [
            'solution' => $solution,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_solution_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Solution $solution, SolutionRepository $solutionRepository): Response
    {
        $form = $this->createForm(SolutionType::class, $solution);
        $form->handleRequest($request);
        $getOldPhoto = $solution->getPhoto();
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            if($photo) {
                $newFilename = 'solution-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('solution_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $solution->setPhoto($newFilename);
           
            }else{
                $solution->setPhoto($getOldPhoto);
            }
            $solutionRepository->add($solution);
            return $this->redirectToRoute('app_solution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('solution/edit.html.twig', [
            'solution' => $solution,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_solution_delete", methods={"POST"})
     */
    public function delete(Request $request, Solution $solution, SolutionRepository $solutionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solution->getId(), $request->request->get('_token'))) {
            $solutionRepository->remove($solution);
        }

        return $this->redirectToRoute('app_solution_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/archiver/{id}/solution", name="archiver_solution")
     *
     * @param Solution $solution
     * @return void
     */
    public function archiver(Solution $solution){
        $solution->setEstArchive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('app_solution_index');
    }
}

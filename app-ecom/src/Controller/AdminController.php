<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /// liste des fonctionnalités de categories

    // ajouter category

    /**
     * @Route("/add/category", name="add_category")
     */
    public function addCategory(Request $request){
        $category = new Category();
        // formualire 
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // appeller le manager du doctrine
            $doctrine = $this->getDoctrine();
            $manager  = $doctrine->getManager();
            // nsob el category
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('info', "la categorie a ete ajouteé avec success");
            return $this->redirectToRoute('list_category');
        }
        // affichage
        return $this->render('admin/category/add-category.html.twig', ['form' => $form->createView( )]);
    }

     /**
     * @Route("/list/category", name="list_category")
     */
    public function listCategory(Request $request){
        $doctrine = $this->getDoctrine();
        $repo     = $doctrine->getRepository(Category::class);
        $categories = $repo->findAll();
        return $this->render('admin/category/list-category.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/edit/category/{id}", name="edit_category")
     */
    public function editCategory(Request $request, Category $category){
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // appeller le manager du doctrine
            $doctrine = $this->getDoctrine();
            $manager  = $doctrine->getManager();
            // nsob el category
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('info', "la categorie a ete modifie avec success");
            return $this->redirectToRoute('list_category');
        }
        return $this->render('admin/category/edit-category.html.twig', ['form' => $form->createView()]);
    }

     /**
     * @Route("/delete/category/{id}", name="delete_category")
     */
    public function deleteCategory(Request $request, Category $category){
        
            // appeller le manager du doctrine
            $doctrine = $this->getDoctrine();
            $manager  = $doctrine->getManager();
            // nsob el category
            $manager->remove($category);
            $manager->flush();
            $this->addFlash('info', "la categorie a ete supprimé avec success");
            return $this->redirectToRoute('list_category');
       
    }

    
     /**
     * @Route("/listProductByCategory/category/{id}", name="liste_produit_category")
     */
    public function listeProduitsParCategorie(Request $request, Category $category){
        
        // appeller le manager du doctrine
        $doctrine = $this->getDoctrine();
        $repo  = $doctrine->getRepository(Product::class);
        $products=$repo->findBy(['category' => $category]);
        
        return $this->render('admin/category/listProductByCategory.html.twig',['products'=>$products]);
   
}
}

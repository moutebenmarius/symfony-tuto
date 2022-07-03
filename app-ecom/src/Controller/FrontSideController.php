<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontSideController extends AbstractController
{
    /**
     * @Route("/", name="site")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('front_side/index.html.twig', [
            'categories' => $categories,
        ]);
    }

     /**
     * @Route("/product/detail/{id}", name="get_product_detail")
     */
    public function productDetail(Product $product): Response
    {
        return $this->render('front_side/product-detail.html.twig');
    }


      /**
     * @Route("/products-by-category/{id}", name="get_products_by_category")
     */
    public function getProductsByCategory(Category $category): Response
    {
        return $this->render('front_side/our-products.html.twig');
    }



    /**
     * @Route("/our-products", name="our_products")
     */
    public function ourProducts(): Response
    {
        return $this->render('front_side/our-products.html.twig');
    }

    /**
     * @Route("/bonjour/{name}", name="bonjour_pour_quelqun")
     */
    public function direBonjour($name): Response
    {
        return $this->render('bonjour.html.twig', ['name' => $name]);
    }

    

}

<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/{id}/buy', name: 'app_product_buy', methods: ['GET'])]
    public function buy(Product $product, EntityManagerInterface $entityManager): Response
    {
        $newQuantity = $product->getQuantity() - 1;
        $product->setQuantity($newQuantity);
        $entityManager->persist($product);
        $entityManager->flush();


        return $this->redirectToRoute("app_product", [], Response::HTTP_SEE_OTHER);
    }
}

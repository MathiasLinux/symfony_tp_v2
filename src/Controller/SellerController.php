<?php

namespace App\Controller;

use App\Entity\Seller;
use App\Form\AddSellerFormType;
use App\Repository\SellerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seller')]
class SellerController extends AbstractController
{
    #[Route('/', name: 'app_seller', methods: ['GET'])]
    public function index(SellerRepository $sellerRepository): Response
    {
        return $this->render('seller/index.html.twig', [
            'sellers' => $sellerRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_seller_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seller = new Seller();
        $form = $this->createForm(AddSellerFormType::class, $seller);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $entityManager->persist($seller);
            $entityManager->flush();

            return $this->redirectToRoute('app_seller', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seller/new.html.twig', [
            'seller' => $seller,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_seller_show', methods: ['GET'])]
    public function show(Seller $seller): Response
    {
        return $this->render('seller/show.html.twig', [
            'seller' => $seller,
        ]);
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'Monsieur ProductController',
        ]);
    }

    #[Route('/product/add', name: 'app_add_product')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();


            return $this->redirectToRoute('app_product');
        }



        return $this->render('product/add.html.twig', [

            'form' => $form->createView(),
        ]);
    }
}

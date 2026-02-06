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
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
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
            $this->addFlash('success', 'Product added successfully!');
            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/add.html.twig', [

            'form' => $form->createView(),
        ]);
    }


    #[Route('/product/{id}', name: 'app_delete_product', methods: ['GET'])]
    public function deleteProduct(int $id, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);
        if ($product) {
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Product deleted successfully!');

            return $this->redirectToRoute('app_product');
        }
        $this->addFlash('error', 'Product not found!');
        return $this->redirectToRoute('app_product');
    }



    #[Route('/product/edit/{id}', name: 'app_edit_product')]
    public function editproduct(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Product edited successfully!');
            return $this->redirectToRoute('app_product');
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

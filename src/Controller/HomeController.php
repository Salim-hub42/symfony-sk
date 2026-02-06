<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();


        return $this->render('home/index.html.twig', [
            "message" => "Magasin de CD et de vinyles de Salim !",
            "products" => $products
        ]);
    }

    #[Route("/Contact", name: "app_contact")]
    public function contact(): Response
    {
        return $this->render("home/contact.html.twig", [
            "wellcome" => "Salim",
            "nom" => "Khalfoun",
            "tel" => "0695162037"
        ]);
    }
}

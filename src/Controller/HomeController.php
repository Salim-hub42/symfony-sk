<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Monsieur Khalfoun',
        ]);
    }

    #[Route("/Contact", name: "app_contact")]
    public function contact(): Response
    {
        return $this->render("contact/contact.html.twig", [
            "wellcome" => "Salim",
            "nom" => "Khalfoun",
            "tel" => "0695162037"
        ]);
    }
}

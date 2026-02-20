<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();


        return $this->render('home/index.html.twig', [

            "products" => $products
        ]);
    }

    #[Route("/Contact", name: "app_contact")]
    public function mail(Request $request, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('name');
            $from = $request->request->get('email');
            $message = $request->request->get('message');

            $email = (new Email())
                ->from($from)
                ->to('salim@gmail.com')
                ->subject('Nouveau message de contact')
                ->text("Nom: $nom\nEmail: $from\nMessage: $message");
            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé !');
        }

        return $this->render("home/contact.html.twig", []);
    }
}

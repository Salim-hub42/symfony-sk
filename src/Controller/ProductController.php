<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


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
        $path = $this->getParameter('app.dir.public') . 'uploads/';

        if ($form->isSubmitted() && $form->isValid()) {

            $file  = $form['image']->getData(); // Récupère le fichier uploadé depuis le formulaire
            //dd($file); // Débogage : affiche les infos du fichier
            if ($file) { // Vérifie si un fichier a été uploadé
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Récupère le nom original du fichier sans l'extension
                $newFilename = 'uploads/' . $originalFilename . '-' . uniqid() . '.' . $file->guessExtension(); // Crée un nom de fichier unique pour éviter les conflits
                $product->setImage($newFilename); // Enregistre le nom du fichier dans l'entité Product

                try {
                    $file->move($path, $newFilename); // Déplace le fichier uploadé vers le dossier de destination
                } catch (FileException $e) {
                    // Gérer l'exception si le déplacement du fichier échoue
                    echo "Erreur lors du téléchargement de l'image : " . $e->getMessage(); // Affiche un message d'erreur si le déplacement échoue
                }
            }


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
        if ($product) { // If the product exists, remove it
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
        $path = $this->getParameter('app.dir.public') . 'uploads/';


        if ($form->isSubmitted() && $form->isValid()) {

            $file  = $form['image']->getData(); // Récupère le fichier uploadé depuis le formulaire
            //dd($file); // Débogage : affiche les infos du fichier
            if ($file) { // Vérifie si un fichier a été uploadé
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Récupère le nom original du fichier sans l'extension
                $newFilename = 'uploads/' . $originalFilename . '-' . uniqid() . '.' . $file->guessExtension(); // Crée un nom de fichier unique pour éviter les conflits
                $product->setImage($newFilename); // Enregistre le nom du fichier dans l'entité Product

                try {
                    $file->move($path, $newFilename); // Déplace le fichier uploadé vers le dossier de destination
                } catch (FileException $e) {
                    // Gérer l'exception si le déplacement du fichier échoue
                    echo "Erreur lors du téléchargement de l'image : " . $e->getMessage(); // Affiche un message d'erreur si le déplacement échoue
                }
            }



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

<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,

        ]);
    }

    #[Route('/category/add', name: 'app_add_category')]
    public function addCategory(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        $path = $this->getParameter('app.dir.public') . 'uploads/';

        if ($form->isSubmitted() && $form->isValid()) {

            $file  = $form['image']->getData(); // Récupère le fichier uploadé depuis le formulaire
            //dd($file); // Débogage : affiche les infos du fichier
            if ($file) { // Vérifie si un fichier a été uploadé
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Récupère le nom original du fichier sans l'extension
                $newFilename = 'uploads/' . $originalFilename . '-' . uniqid() . '.' . $file->guessExtension(); // Crée un nom de fichier unique pour éviter les conflits
                $category->setImage($newFilename); // Enregistre le nom du fichier dans l'entité Category

                try {
                    $file->move($path, $newFilename); // Déplace le fichier uploadé vers le dossier de destination
                } catch (FileException $e) {
                    // Gérer l'exception si le déplacement du fichier échoue
                    echo "Erreur lors du téléchargement de l'image : " . $e->getMessage(); // Affiche un message d'erreur si le déplacement échoue
                }
            }

            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Category added successfully!');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}', name: 'app_delete_category', methods: ['POST'])]
    public function deleteCategory(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $category = $em->getRepository(Category::class)->find($id);
        if (!$category) {
            $this->addFlash('error', 'Category not found!');
            return $this->redirectToRoute('app_category');
        }

        $submittedToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $submittedToken)) {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Category deleted successfully!');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide, suppression refusée.');
        }
        return $this->redirectToRoute('app_category');
    }

    # création d'editCategory ici
    #[Route('/category/edit/{id}', name: 'app_edit_category')]
    public function editcategory(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        $path = $this->getParameter('app.dir.public') . 'uploads/';


        if ($form->isSubmitted() && $form->isValid()) {
            $file  = $form['image']->getData(); // Récupère le fichier uploadé depuis le formulaire
            if ($file) { // Vérifie si un fichier a été uploadé
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Récupère le nom original du fichier sans l'extension
                $newFilename = 'uploads/' . $originalFilename . '-' . uniqid() . '.' . $file->guessExtension(); // Crée un nom de fichier unique pour éviter les conflits
                $category->setImage($newFilename); // Enregistre le nom du fichier dans l'entité Category   
                try {
                    $file->move($path, $newFilename); // Déplace le fichier uploadé vers le dossier de destination
                } catch (FileException $e) {
                    // Gérer l'exception si le déplacement du fichier échoue
                    echo "Erreur lors du téléchargement de l'image : " . $e->getMessage(); // Affiche un message d'erreur si le déplacement échoue
                }
            }


            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Category edited successfully!');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class UserController extends AbstractController
{

    #[Route('/dashboard', name: 'app_user_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {


        $users = $entityManager->getRepository(User::class)->findAll();





        $message = 'Bienvenue admin sur votre tableau de bord !';
        return $this->render('user/gestion-admin.html.twig', [
            'message' => $message,
            'users' => $users, // Récupère tous les utilisateurs depuis la base de données et les passe à la vue
        ]);
    }








    // ici pour que l'utilisateur puisse accéder à son tableau de bord
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $message = 'Votre tableau de bord utilisateur !';

        return $this->render('user/index.html.twig', [
            'message' => $message,
        ]);
    }


    //**  Méthode CRUD  **//

    // ici pour que l'utilisateur puisse créer un compte, éditer ses informations, voir son profil et supprimer son compte

    #[Route('/new', name: 'app_user_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User(); // Crée une nouvelle instance de User depuis l'entité User
        $form = $this->createForm(UserType::class, $user, ['include_email' => true]); // Crée un formulaire basé sur la classe UserType et lie les données à l'objet $user
        $form->handleRequest($request); // Traite la requête et remplit le formulaire avec les données soumises

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère le mot de passe du formulaire
            $PasswordForm = $form->get('password')->getData(); // Récupère le mot de passe du formulaire
            // Hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $PasswordForm); // Hash le mot de passe en utilisant le UserPasswordHasherInterface
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully!');
            return $this->redirectToRoute('app_home');
        }


        return $this->render('user/new.html.twig', [
            'controller_name' => 'Formulaire de création de compte',
            'form' => $form->createView(),
        ]);
    }



    #[Route('/edit/{id}', name: 'app_user_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user, [
            'include_email' => false,
            'is_admin' => $this->isGranted('ROLE_ADMIN')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { // Vérifie si le formulaire a été soumis et est valide
            $passwordForm = $form->get('password')->getData(); // Récupère le mot de passe du formulaire
            if (!empty($passwordForm)) { // Vérifie si un mot de passe a été saisi dans le formulaire
                $hashedPassword = $passwordHasher->hashPassword($user, $passwordForm); // Hash le mot de passe en utilisant le UserPasswordHasherInterface
                $user->setPassword($hashedPassword); // Met à jour le mot de passe de l'utilisateur avec le mot de passe hashé
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'User edited successfully!');
            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }




    #[Route('/show/{id}', name: 'app_user_show')]
    public function show(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {

        $user = $entityManager->getRepository(User::class)->find($id); // Récupère l'utilisateur connecté à partir de la base de données en utilisant l'EntityManagerInterface et le repository de l'entité User
        $form = $this->createForm(UserType::class, $user, ['include_email' => false]);
        $form->handleRequest($request);


        return $this->render('user/show.html.twig', [
            'controller_name' => 'UserController',
            'message' => 'View your user information here !',
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }




    #[Route('/delete/{id}', name: 'app_user_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id); // Récupère l'utilisateur connecté à partir de la base de données en utilisant l'EntityManagerInterface et le repository de l'entité User
        if ($user) { // Si l'utilisateur existe, le supprimer
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'User deleted successfully!');
            return $this->redirectToRoute('app_home');
        }


        return $this->render('user/delete.html.twig', [
            'controller_name' => 'UserController',
            'message' => 'Delete your user account here !',
        ]);
    }
}

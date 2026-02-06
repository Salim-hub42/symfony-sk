<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    // ici pour que l'utilisateur puisse accéder à son tableau de bord
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'message' => 'Ici vous pouvez accéder à votre tableau de bord !',
        ]);
    }


    // ici pour que l'utilisateur puisse créer un compte, éditer ses informations, voir son profil et supprimer son compte
    #[Route('/new', name: 'app_user_new')]
    public function new(): Response
    {
        return $this->render('user/new.html.twig', [
            'controller_name' => 'UserController',
            'message' => 'Create a new user here !',
        ]);
    }

    #[Route('/edit', name: 'app_user_edit')]
    public function edit(): Response
    {
        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'message' => 'Edit your user information here !',
        ]);
    }

    #[Route('/show', name: 'app_user_show')]
    public function show(): Response
    {
        return $this->render('user/show.html.twig', [
            'controller_name' => 'UserController',
            'message' => 'View your user information here !',
        ]);
    }


    #[Route('/delete', name: 'app_user_delete')]
    public function delete(): Response
    {
        return $this->render('user/delete.html.twig', [
            'controller_name' => 'UserController',
            'message' => 'Delete your user account here !',
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin', name: 'admin_')]
class UsersController extends AbstractController
{
    #[Route('/utilisateurs', name: 'users')]
    public function index(Users $users, UsersRepository $usersRepository): Response
    {
        $users = $usersRepository->findAll([]);

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }
}

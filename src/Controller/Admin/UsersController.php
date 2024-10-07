<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Form\UserFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin', name: 'admin_')]
class UsersController extends AbstractController
{
    #[Route('/utilisateurs', name: 'users')]
    public function index(Users $users, UsersRepository $usersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $usersRepository->findAll([]);

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/utilisateurs/ajout', name: 'users_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new Users();

        // On créé le formulaire
        $userForm = $this->createForm(UserFormType::class, $user);

        // On traite la requête
        $userForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur ajouté avec succès');
        }

        return $this->render('admin/users/add.html.twig', [
            'userForm' => $userForm
        ]);
    }

    #[Route('/utilisateurs/edition/{id}', name: 'users_edit')]
    public function edit(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->redirectToRoute('admin_users');
    }

    #[Route('utilisateurs/suppression/{id}', name: 'users_delete')]
    public function delete(Users $user, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        } else {
            $this->addFlash('danger', "Échec de la suppression de l'utilisateur");
        }

        return $this->redirectToRoute('admin_users');
    }
}

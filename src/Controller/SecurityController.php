<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use App\Service\SendEmailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Route qui permet en donnant mon adresse mail d'avoir un lien
     *
     * @param Request $request
     * @param UsersRepository $usersRepository
     * @param TokenGeneratorInterface $tokenGeneratorInterface
     * @param EntityManagerInterface $manager
     * @param SendEmailService $mailer
     * @return Response
     */
    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(Request $request, UsersRepository $usersRepository, TokenGeneratorInterface $tokenGeneratorInterface, EntityManagerInterface $manager, SendEmailService $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        // Pour gérer la requête, on l'appelle 'request'
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On va chercher l'utilisateur par son email
            $email = $form->get('email')->getData();
            $user = $usersRepository->findOneByEmail($email);

            // On vérifie si on a un utilisateur
            if ($user) {
                // On génère un token de réinitialisation
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $manager->persist($user);
                $manager->flush();

                // On génère un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // On créé les données du mail
                $context = [
                    'url' => $url,
                    'user' => $user
                ];

                // Envoi du mail
                $mailer->send(
                    'infos_warelles@gmail.com',
                    $user->getEmail(),
                    'Réinitialisation du mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Votre e-mail a été envoyé avec succès');

                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('danger', 'Votre e-mail est inexistant, veuillez réessayer');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'ResetPasswordRequestForm' => $form->createView()
        ]);
    }

    /**
     *  Route qui va permettre en cliquant sur le lien de choisir un autre mot de passe
     */
    #[Route(path: '/oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(string $token, Request $request, UsersRepository $usersRepository, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {

        // On vérifie si on a le token dans la base de données
        $user = $usersRepository->findOneByResetToken($token);

        if ($user) {
            $form = $this->createForm((ResetPasswordFormType::class));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    ),
                );

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }

        // Si token pas valide
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }
}

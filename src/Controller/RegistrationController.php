<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, SendEmailService $mailer, JWTService $jwt): Response
    {
        $user = new Users();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Persist l'utilisateur pour générer l'id
            $entityManager->persist($user);

            // Là, on enregistre l'utilisateur sans le token
            $entityManager->flush();

            // On génère le JWT de l'utilisateur
            // On créé le header
            $header = [
                // on se base sur le site de jwt, même référence
                'typ' => 'jwt',
                'alg' => 'HS256'
            ];

            // On créé le payload 
            $payload = [
                'user_id' => $user->getId()
            ];

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            //On sauvegarge le token dans la base de données
            $user->setResetToken($token);

            // Sauvegardez le token dans la base de données
            $entityManager->flush();

            // On envoie un e-mail
            $mailer->send(
                'no-reply@fermeDeWarelles.be',
                $user->getEmail(),
                'Activation de votre compte sur le site e-commerce',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );

            return $security->login($user, UsersAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {

        // On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {

            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $usersRepository->find($payload['user_id']);

            // On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);

                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('app_login');
            }
        }

        // Ici un problème se pose dans le token
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendEmailService $mailer): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Users) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');

            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');

            return $this->redirectToRoute('app_login');
        }

        // On génère le JWT de l'utilisateur
        // On créé le header
        $header = [
            // on se base sur le site de jwt, même référence
            'typ' => 'jwt',
            'alg' => 'HS256'
        ];

        // On créé le payload 
        $payload = [
            'user_id' => $user->getId()
        ];

        // On génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // On envoie un e-mail
        $mailer->send(
            'no-reply@fermeDeWarelles.be',
            $user->getEmail(),
            'Activation de votre compte sur le site e-commerce',
            'register',
            [
                'user' => $user,
                'token' => $token
            ]
        );

        $this->addFlash('success', 'Email de vérification envoyé');

        return $this->redirectToRoute('app_login');
    }
}

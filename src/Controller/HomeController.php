<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, CategoriesRepository $categoriesRepository, MailerInterface $mailer): Response
    {
        $categories = $categoriesRepository->findAll();

        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            $recaptchaResponse = $request->get('g-recaptcha-response');

            // Vérification du reCAPTCHA
            $client = HttpClient::create();
            $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'query' => [
                    'secret' => ($_ENV['reCAPTCHA_SECRET_KEY']),
                    'response' => $recaptchaResponse,
                ],
            ]);

            // Envoi de l'e-mail
            $this->sendEmail(
                $contactFormData['email'],
                'infos_warelles@gmail.com',
                'Question client',
                [
                    'firstname' => $contactFormData['firstname'],
                    'lastname' => $contactFormData['lastname'],
                    'user_email' => $contactFormData['email'],
                    'user_address' => $contactFormData['address'],
                    'message' => $contactFormData['message'],
                ],
                $mailer
            );

            $this->addFlash('success', 'Votre message a été envoyé avec succès!');

            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    private function sendEmail(string $from, string $to, string $subject, array $context, MailerInterface $mailer): void
    {
        // Construire le contenu de l'e-mail
        $emailContent = sprintf(
            "Nom: %s\nPrénom: %s\nE-mail: %s\nAdresse: %s\nMessage:\n%s",
            $context['lastname'],
            $context['firstname'],
            $context['user_email'],
            $context['user_address'],
            $context['message']
        );

        // Créer l'objet de l'e-mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($emailContent);

        // Envoyer l'e-mail
        $mailer->send($email);
    }
}

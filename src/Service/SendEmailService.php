<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailService
{
    public function __construct(private MailerInterface $mailer) {}

    public function send(
        // Représente l'adresse e-mail
        string $from,
        // Représente l'adresse e-mail du destinataire
        string $to,
        // Représente le sujet du mail
        string $subject,
        // Représente le nom du template à utiliser pour le contenu du mail
        string $template,
        // Utilisé pour le passage de variables au template twig.
        array $context

    ): void {

        // On créé le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        // On envoie le mail
        $this->mailer->send($email);
    }
}

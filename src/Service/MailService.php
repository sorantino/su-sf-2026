<?php

namespace App\Service;

use App\Entity\Newsletter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail
    ) {
    }

    public function confirmSubscription(Newsletter $newsletter)
    {
        $email = new Email();
        $email
            ->from($this->adminEmail)
            ->to($newsletter->getEmail())
            ->subject("Inscription à la newsletter")
            ->text("Votre inscription a bien été prise en compte, merci.");

        $this->mailer->send($email);
    }
}
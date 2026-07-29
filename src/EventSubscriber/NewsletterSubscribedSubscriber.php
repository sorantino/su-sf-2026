<?php

namespace App\EventSubscriber;

use App\Event\NewsletterSubscribedEvent;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\Bridge\Discord\DiscordOptions;
use Symfony\Component\Notifier\Bridge\Discord\Embeds\DiscordEmbed;
use Symfony\Component\Notifier\Bridge\Discord\Embeds\DiscordFieldEmbedObject;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

class NewsletterSubscribedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MailService $mailService,
        private ChatterInterface $chatter
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NewsletterSubscribedEvent::NAME => [
                ['sendConfirmationEmail', 10],
                ['sendDiscordNotification', 5]
            ],
        ];
    }

    public function sendConfirmationEmail(NewsletterSubscribedEvent $event): void
    {
        $newsletter = $event->newsletter;

        $this->mailService->confirmSubscription($newsletter);
    }

    public function sendDiscordNotification(NewsletterSubscribedEvent $event): void
    {
        $newsletter = $event->newsletter;
        $chatMessage = new ChatMessage('');

        $discordOptions = new DiscordOptions()
            ->username('SU SF 2026')
            ->addEmbed(
                new DiscordEmbed()
                    ->title('Nouvelle inscription à la newsletter')
                    ->addField(
                        new DiscordFieldEmbedObject()
                            ->name('Email')
                            ->value($newsletter->getEmail())
                            ->inline(true)
                    )
                    ->addField(
                        new DiscordFieldEmbedObject()
                            ->name('Date')
                            ->value(new \DateTimeImmutable()->format('d/m/Y H:i'))
                            ->inline(true)
                    )
            );

        $chatMessage->options($discordOptions);
        $this->chatter->send($chatMessage);
    }
}

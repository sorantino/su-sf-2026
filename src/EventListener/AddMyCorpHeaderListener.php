<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::RESPONSE, method: 'addHeader')]
final class AddMyCorpHeaderListener
{
    public function addHeader(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $response->headers->add([
            'X-DEVELOPED-BY' => 'Cequevousvoulez'
        ]);
    }
}

<?php

namespace App\Event;

use App\Entity\Newsletter;

class NewsletterSubscribedEvent
{
    public const string NAME = "newsletter.subscribed";

    public function __construct(
        public readonly Newsletter $newsletter
    ) {
    }
}
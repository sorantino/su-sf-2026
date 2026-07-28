<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe')]
    public function subscribe(Request $request, EntityManagerInterface $em): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newsletter);
            $em->flush();
        }

        return $this->render('newsletter/subscribe.html.twig', [
            'newsletterForm' => $form,
        ]);
    }
}
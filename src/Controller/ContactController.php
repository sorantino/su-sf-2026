<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/contact/add', name: 'contact_add')]
    public function contactAdd(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new DateTimeImmutable());
            $em->persist($contact);
            $em->flush();
        }

        return $this->render('contact/add.html.twig', [
            'contactForm' => $form
        ]);
    }
}

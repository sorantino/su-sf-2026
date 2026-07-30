<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route('/articles/add', name: 'article_add')]
    public function articleAdd(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article
                ->setCreatedAt(new DateTimeImmutable())
                ->setSlug($slugger->slug($article->getTitle()));
            $em->persist($article);
            $em->flush();
        }

        return $this->render('article/add.html.twig', [
            'articleForm' => $form
        ]);
    }

    #[Route('/contact/list', name: 'contact_list')]
    public function contactList(ContactRepository $contactRepository): Response {
    $contacts = $contactRepository->findAllContactsByDate();
    return $this->render('contact/list.html.twig', [
        'contacts' => $contacts
    ]);
    }

    #[Route('/articles/edit/{id}', name: 'article_edit')]
    public function articleEdit(
        Request $request,
        Article $article,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ARTICLE_EDIT', $article);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form
        ]);
    }
}

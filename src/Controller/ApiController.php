<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ApiController extends AbstractController
{
    #[Route('/articles', name: 'api_articles')]
    public function articles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->json($articles, context: ['groups' => ['articles:list']]);
    }
}
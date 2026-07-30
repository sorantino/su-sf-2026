<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api')]
final class ApiController extends AbstractController
{
    #[Route('/articles', name: 'api_articles')]
    public function articles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->json($articles, context: ['groups' => ['articles:list']]);
    }

    #[Route('/articles-visibles', name: 'api_articles_visibles')]
    public function visibleArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['visible' => true]);

        return $this->json($articles, context: ['groups' => ['articles:list']]);
    }

    #[Route('/articles/{id}', name: 'api_article_item')]
    public function article(Article $article, UrlGeneratorInterface $urlGenerator): Response
    {
        if (!$article->isVisible()) {
            throw $this->createNotFoundException();
        }
        $tags = array_map(fn ($tag) => $tag->getLabel(), $article->getTags()->toArray());

        return $this->json([
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'createdAt' => $article->getCreatedAt(),
            'category' => $article->getCategory()->getName(),
            'tags' => $tags,
            'url' => $urlGenerator->generate('article_item', ['slug' => $article->getSlug()]),
        ]);
    }
}

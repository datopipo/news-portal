<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\News;
use App\Form\CommentType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewsController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/news/{id}', name: 'app_news_show', requirements: ['id' => '\d+'])]
    public function show(News $news, Request $request): Response
    {
        $news->incrementViewCount();
        $this->entityManager->flush();

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        return $this->render('news/show.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }
}

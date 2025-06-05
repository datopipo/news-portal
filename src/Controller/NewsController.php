<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
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
        private readonly NewsRepository $newsRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/news/{id}', name: 'app_news_show', requirements: ['id' => '\d+'])]
    public function show(int $id, Request $request): Response
    {
        $news = $this->newsRepository->findWithDetails($id);

        if (!$news) {
            throw $this->createNotFoundException('News not found');
        }

        // Increment view count
        $news->incrementViewCount();
        $this->entityManager->flush();

        // Handle comment form
        $comment = new Comment();
        $comment->setNews($news);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your comment has been added successfully!');
            return $this->redirectToRoute('app_news_show', ['id' => $id]);
        }

        return $this->render('news/show.html.twig', [
            'news' => $news,
            'commentForm' => $form->createView(),
        ]);
    }
}

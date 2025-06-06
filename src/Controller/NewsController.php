<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\News;
use App\Form\CommentType;
use App\Repository\NewsRepository;
use App\Service\NewsViewService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/news')]
class NewsController extends AbstractController
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly NewsViewService $newsViewService,
        private readonly PaginationService $paginationService
    ) {
    }

    #[Route('/', name: 'app_news_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $paginationParams = $this->paginationService->getPaginationParameters($request);
        $news = $this->newsRepository->findAllPaginated(
            $paginationParams['page'],
            $paginationParams['limit']
        );

        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }

    #[Route('/{id}', name: 'app_news_show', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function show(Request $request, int $id): Response
    {
        $news = $this->newsRepository->findById($id);
        
        if (!$news) {
            throw $this->createNotFoundException('News article not found.');
        }

        $this->newsViewService->incrementViewCount($news);

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setNews($news);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your comment has been added.');

            return $this->redirectToRoute('app_news_show', ['id' => $news->getId()]);
        }

        return $this->render('news/show.html.twig', [
            'news' => $news,
            'form' => $form,
        ]);
    }
}

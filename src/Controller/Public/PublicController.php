<?php

declare(strict_types=1);

namespace App\Controller\Public;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly NewsRepository $newsRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $categoriesWithNews = [];

        foreach ($categories as $category) {
            $news = $this->newsRepository->findLatestByCategory($category);
            if (!empty($news)) {
                $categoriesWithNews[] = [
                    'category' => $category,
                    'news' => $news,
                ];
            }
        }

        return $this->render('home/index.html.twig', [
            'categoriesWithNews' => $categoriesWithNews,
        ]);
    }

    public function category(Request $request, int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $newsQuery = $this->newsRepository->createByCategoryQuery($category);
        $allNews = $newsQuery->getQuery()->getResult();

        $page = max(1, $request->query->getInt('page', 1));
        $itemsPerPage = $this->getParameter('pagination.items_per_page');
        $totalItems = count($allNews);
        $totalPages = (int) ceil($totalItems / $itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;
        $news = array_slice($allNews, $offset, $itemsPerPage);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'news' => $news,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'hasNextPage' => $page < $totalPages,
            'hasPrevPage' => $page > 1,
        ]);
    }

    public function news(Request $request, int $id): Response
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            throw $this->createNotFoundException('News not found');
        }

        $news->incrementViewCount();
        $this->entityManager->flush();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        return $this->render('news/show.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    public function comment(Request $request, int $id): Response
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            throw $this->createNotFoundException('News not found');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setNews($news);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Comment added successfully.');
        }

        return $this->redirectToRoute('app_public_news', ['id' => $news->getId()]);
    }
}

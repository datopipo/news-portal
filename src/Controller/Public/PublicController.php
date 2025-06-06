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
        // Fix N+1 query problem with single optimized query
        $categoriesWithNews = $this->categoryRepository->findCategoriesWithLatestNews(3);

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

        $page = max(1, $request->query->getInt('page', 1));
        $itemsPerPage = $this->getParameter('pagination.items_per_page');
        
        // Use efficient database-level pagination
        $paginationData = $this->newsRepository->findByCategoryPaginated($category, $page, $itemsPerPage);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'news' => $paginationData['news'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            'totalItems' => $paginationData['totalCount'],
            'hasNextPage' => $paginationData['hasNextPage'],
            'hasPrevPage' => $paginationData['hasPrevPage'],
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
            return $this->redirectToRoute('app_public_news', ['id' => $news->getId()]);
        }

        // If form has errors, show them by rendering the news page with form errors
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please correct the errors in your comment.');
        }

        return $this->render('news/show.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }
}

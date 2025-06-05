<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly NewsRepository $newsRepository,
        private readonly PaginationService $paginationService
    ) {
    }

    #[Route('/{id}', name: 'app_category_show', requirements: ['id' => '\d+'])]
    public function show(int $id, Request $request): Response
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            $this->addFlash('error', 'Category not found.');
            return $this->redirectToRoute('app_home');
        }

        // Use proper pagination service to avoid loading all results
        $queryBuilder = $this->newsRepository->createQueryBuilder('n')
            ->addSelect('categories', 'comments')
            ->leftJoin('n.categories', 'categories')
            ->leftJoin('n.comments', 'comments')
            ->leftJoin('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId())
            ->orderBy('n.insertDate', 'DESC');

        $paginationData = $this->paginationService->paginate($queryBuilder, $request);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'news' => $paginationData['items'],
            'totalItems' => $paginationData['total_items'],
            'totalPages' => $paginationData['total_pages'],
            'currentPage' => $paginationData['current_page'],
            'itemsPerPage' => $paginationData['items_per_page'],
        ]);
    }
}

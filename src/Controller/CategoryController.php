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

    #[Route('/category/{id}', name: 'app_category_show', requirements: ['id' => '\d+'])]
    public function show(int $id, Request $request): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $currentPage = max(1, $request->query->getInt('page', 1));
        $paginator = $this->newsRepository->findPaginatedByCategory($category, $currentPage);

        return $this->render('category/show.html.twig', array_merge(
            [
                'category' => $category,
                'news' => $paginator,
            ],
            $this->paginationService->getPaginationData(count($paginator), $currentPage)
        ));
    }
}

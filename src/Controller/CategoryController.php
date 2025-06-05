<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{id}', name: 'app_category_show', requirements: ['id' => '\d+'])]
    public function show(
        int $id,
        Request $request,
        CategoryRepository $categoryRepository,
        NewsRepository $newsRepository
    ): Response {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $page = max(1, $request->query->getInt('page', 1));
        $paginator = $newsRepository->findPaginatedByCategory($category, $page, 10);

        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / 10);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'news' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
        ]);
    }
}

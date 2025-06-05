<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constants\SecurityConstants;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/category')]
class AdminCategoryController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CategoryRepository $categoryRepository,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly SecurityConstants $securityConstants
    ) {
    }

    #[Route('/', name: 'app_admin_category_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->renderIndex('admin/category/index.html.twig', $this->categoryRepository->findAll());
    }

    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $category = new Category();
        return $this->handleNew(
            $request,
            $this->entityManager,
            $category,
            CategoryType::class,
            'admin/category/new.html.twig',
            'Category created successfully.',
            'app_admin_category_index'
        );
    }

    #[Route('/{id}', name: 'app_admin_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category): Response
    {
        return $this->handleEdit(
            $request,
            $this->entityManager,
            $category,
            CategoryType::class,
            'admin/category/edit.html.twig',
            'Category updated successfully.',
            'app_admin_category_index'
        );
    }

    #[Route('/{id}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category): Response
    {
        return $this->handleDelete(
            $request,
            $this->entityManager,
            $category,
            'delete',
            'Category deleted successfully.',
            'app_admin_category_index'
        );
    }

    protected function getResourceName(): string
    {
        return 'categories';
    }
}

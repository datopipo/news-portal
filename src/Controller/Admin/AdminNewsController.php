<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Constants\SecurityConstants;
use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Service\FileUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/news')]
class AdminNewsController extends AbstractCrudController
{
    public function __construct(
        private readonly FileUploadService $fileUploadService,
        private readonly EntityManagerInterface $entityManager,
        private readonly NewsRepository $newsRepository,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly SecurityConstants $securityConstants
    ) {
    }

    #[Route('/', name: 'app_admin_news_index')]
    public function index(): Response
    {
        return $this->renderIndex('admin/news/index.html.twig', $this->newsRepository->findAll());
    }

    #[Route('/new', name: 'app_admin_news_new')]
    public function new(Request $request): Response
    {
        $news = new News();
        return $this->handleNew(
            $request,
            $this->entityManager,
            $news,
            NewsType::class,
            'admin/news/new.html.twig',
            'News created successfully!',
            'app_admin_news_index',
            function($form, $news) {
                $this->fileUploadService->handleFormUpload($form, 'pictureFile', $news, 'setPicture');
            }
        );
    }

    #[Route('/{id}/edit', name: 'app_admin_news_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $news = $this->newsRepository->find($id);
        
        if (!$news) {
            $this->addFlash('error', 'News item not found.');
            return $this->redirectToRoute('app_admin_news_index');
        }

        return $this->handleEdit(
            $request,
            $this->entityManager,
            $news,
            NewsType::class,
            'admin/news/edit.html.twig',
            'News updated successfully!',
            'app_admin_news_index',
            function($form, $news) {
                $this->fileUploadService->handleFormUpload($form, 'pictureFile', $news, 'setPicture');
            }
        );
    }

    #[Route('/{id}/delete', name: 'app_admin_news_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, News $news): Response
    {
        return $this->handleDelete(
            $request,
            $this->entityManager,
            $news,
            'delete',
            'News deleted successfully!',
            'app_admin_news_index'
        );
    }

    protected function getResourceName(): string
    {
        return 'news';
    }
}

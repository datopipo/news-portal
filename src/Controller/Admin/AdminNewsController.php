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
        return $this->handleNewsForm($request, $news, 'admin/news/new.html.twig', 'News created successfully!', true);
    }

    #[Route('/{id}/edit', name: 'app_admin_news_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $news = $this->newsRepository->find($id);
        
        if (!$news) {
            $this->addFlash('error', 'News item not found.');
            return $this->redirectToRoute('app_admin_news_index');
        }

        return $this->handleNewsForm($request, $news, 'admin/news/edit.html.twig', 'News updated successfully!', false);
    }

    private function handleNewsForm(Request $request, News $news, string $template, string $successMessage, bool $isNew): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', 'Validation Error: ' . $error->getMessage());
                }
            } else {
                try {
                    // Handle file upload if present
                    try {
                        $this->fileUploadService->handleFormUpload($form, 'imageFile', $news, 'setPicture');
                    } catch (\Exception $uploadError) {
                        $this->addFlash('warning', 'File upload failed: ' . $uploadError->getMessage());
                        // Continue saving without image
                    }
                    
                    if ($isNew) {
                        $this->entityManager->persist($news);
                    }
                    $this->entityManager->flush();

                    $this->addFlash('success', $successMessage);
                    return $this->redirectToRoute('app_admin_news_index');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Error saving news: ' . $e->getMessage());
                }
            }
        }

        return $this->render($template, [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_news_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, int $id): Response
    {
        $news = $this->newsRepository->find($id);
        
        if (!$news) {
            $this->addFlash('error', 'News article not found.');
            return $this->redirectToRoute('app_admin_news_index');
        }

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

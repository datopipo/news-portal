<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminNewsController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NewsRepository $newsRepository
    ) {
    }

    public function index(): Response
    {
        $news = $this->newsRepository->findBy([], ['insertDate' => 'DESC']);
        return $this->render('admin/news/index.html.twig', [
            'news' => $news,
        ]);
    }

    public function new(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFileUpload($form, $news);

            $this->entityManager->persist($news);
            $this->entityManager->flush();

            $this->addFlash('success', 'News created successfully.');
            return $this->redirectToRoute('app_admin_news_index');
        }

        return $this->render('admin/news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    public function show(int $id): Response
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            throw $this->createNotFoundException('News not found');
        }

        return $this->render('admin/news/show.html.twig', [
            'news' => $news,
        ]);
    }

    public function edit(Request $request, int $id): Response
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            throw $this->createNotFoundException('News not found');
        }

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFileUpload($form, $news);

            $this->entityManager->flush();

            $this->addFlash('success', 'News updated successfully.');
            return $this->redirectToRoute('app_admin_news_index');
        }

        return $this->render('admin/news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, int $id): Response
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            throw $this->createNotFoundException('News not found');
        }

        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($news);
            $this->entityManager->flush();
            $this->addFlash('success', 'News deleted successfully.');
        }

        return $this->redirectToRoute('app_admin_news_index');
    }

    private function handleFileUpload($form, News $news): void
    {
        $pictureFile = $form->get('pictureFile')->getData();
        if ($pictureFile) {
            // Security: Validate file type and extension
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

            $extension = strtolower($pictureFile->guessExtension());
            $mimeType = $pictureFile->getMimeType();

            if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
                throw new InvalidArgumentException('Invalid file type. Only JPG, PNG and GIF images are allowed.');
            }

            // Security: Sanitize filename completely
            $safeFilename = 'news-image-' . uniqid() . '.' . $extension;

            try {
                $pictureFile->move($this->getParameter('pictures_directory'), $safeFilename);

                // Clean up old image if exists
                if ($news->getPicture()) {
                    $oldImagePath = $this->getParameter('pictures_directory') . '/' . $news->getPicture();
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $news->setPicture($safeFilename);
            } catch (Exception $e) {
                throw new RuntimeException('Failed to upload image: ' . $e->getMessage());
            }
        }
    }
}

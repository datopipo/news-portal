<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Constants\AppConstants;

#[Route('/admin/news')]
class AdminNewsController extends AbstractController
{
    private function handleFileUpload($form, $news, $slugger): void
    {
        $pictureFile = $form->get('pictureFile')->getData();
        if (!$pictureFile) {
            return;
        }

        $newFilename = $slugger->slug(pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . uniqid() . '.' . $pictureFile->guessExtension();

        try {
            $pictureFile->move($this->getParameter('pictures_directory'), $newFilename);
            $news->setPicture($newFilename);
        } catch (FileException $e) {
            $this->addFlash('error', 'Error uploading file: ' . $e->getMessage());
        }
    }

    #[Route('/', name: 'admin_news_index')]
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('admin/news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_news_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFileUpload($form, $news, $slugger);
            $entityManager->persist($news);
            $entityManager->flush();

            $this->addFlash('success', 'News created successfully!');
            return $this->redirectToRoute('admin_news_index');
        }

        return $this->render('admin/news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_news_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, News $news, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFileUpload($form, $news, $slugger);
            $entityManager->flush();

            $this->addFlash('success', 'News updated successfully!');
            return $this->redirectToRoute('admin_news_index');
        }

        return $this->render('admin/news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_news_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid(AppConstants::getCsrfTokenIdNews() . $news->getId(), $request->request->get('_token'))) {
            $entityManager->remove($news);
            $entityManager->flush();
            $this->addFlash('success', 'News deleted successfully!');
        }

        return $this->redirectToRoute('admin_news_index');
    }
}

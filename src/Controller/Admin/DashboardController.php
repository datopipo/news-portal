<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(
        NewsRepository $newsRepository,
        CategoryRepository $categoryRepository,
        CommentRepository $commentRepository
    ): Response {
        $totalNews = count($newsRepository->findAll());
        $totalCategories = count($categoryRepository->findAll());
        $totalComments = count($commentRepository->findAll());
        $topNews = $newsRepository->findTopByViews(5);
        $latestNews = $newsRepository->findLatest(5);
        $latestComments = $commentRepository->findAllOrderedByDate();
        $latestComments = array_slice($latestComments, 0, 5);

        return $this->render('admin/dashboard/index.html.twig', [
            'totalNews' => $totalNews,
            'totalCategories' => $totalCategories,
            'totalComments' => $totalComments,
            'topNews' => $topNews,
            'latestNews' => $latestNews,
            'latestComments' => $latestComments,
        ]);
    }
}

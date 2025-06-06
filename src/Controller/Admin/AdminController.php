<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\NewsRepository;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly CommentRepository $commentRepository
    ) {
    }

    public function index(): Response
    {
        // Get counts for dashboard stats (efficient COUNT queries)
        $totalNews = $this->newsRepository->count([]);
        $totalCategories = $this->categoryRepository->count([]);
        $totalComments = $this->commentRepository->count([]);

        // Get top news by views (limit 5)
        $topNews = $this->newsRepository->findTopByViews(5);

        // Get latest news (limit 5)
        $latestNews = $this->newsRepository->findBy([], ['insertDate' => 'DESC'], 5);

        // Get latest comments (limit 10)
        $latestComments = $this->commentRepository->findBy([], ['createdAt' => 'DESC'], 10);

        return $this->render('admin/dashboard/index.html.twig', [
            'totalNews' => $totalNews,
            'totalCategories' => $totalCategories,
            'totalComments' => $totalComments,
            'topNews' => $topNews,
            'latestNews' => $latestNews,
            'latestComments' => $latestComments,
        ]);
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    public function logout(): void
    {
        throw new LogicException('method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

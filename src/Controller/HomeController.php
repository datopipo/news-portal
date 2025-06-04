<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository, NewsRepository $newsRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $categoriesWithNews = [];

        foreach ($categories as $category) {
            $recentNews = $newsRepository->findRecentByCategory($category, 3);
            $categoriesWithNews[] = [
                'category' => $category,
                'news' => $recentNews
            ];
        }

        return $this->render('home/index.html.twig', [
            'categoriesWithNews' => $categoriesWithNews,
        ]);
    }
}

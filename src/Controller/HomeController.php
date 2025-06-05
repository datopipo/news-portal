<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\HomePageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly HomePageService $homePageService
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'categoriesWithNews' => $this->homePageService->getCategoriesWithNews(),
        ]);
    }
}

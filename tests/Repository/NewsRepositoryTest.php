<?php

namespace App\Tests\Repository;

use App\Entity\News;
use App\Entity\Category;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class NewsRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private NewsRepository $newsRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->newsRepository = $this->entityManager->getRepository(News::class);
    }

    public function testFindRecentByCategory(): void
    {
        // Create a test category
        $category = new Category();
        $category->setTitle('Test Category');
        $this->entityManager->persist($category);

        // Create test news articles
        for ($i = 0; $i < 5; $i++) {
            $news = new News();
            $news->setTitle("Test News $i");
            $news->setShortDescription("Test Description $i");
            $news->setContent("Test Content $i");
            $news->addCategory($category);
            $this->entityManager->persist($news);
        }

        $this->entityManager->flush();

        // Test finding recent news by category
        $recentNews = $this->newsRepository->findRecentByCategory($category, 3);
        $this->assertCount(3, $recentNews);
        $this->assertInstanceOf(News::class, $recentNews[0]);
    }

    public function testFindTopByViews(): void
    {
        // Create test news articles with different view counts
        for ($i = 0; $i < 5; $i++) {
            $news = new News();
            $news->setTitle("Test News $i");
            $news->setShortDescription("Test Description $i");
            $news->setContent("Test Content $i");
            $news->setViewCount($i * 100);
            $this->entityManager->persist($news);
        }

        $this->entityManager->flush();

        // Test finding top news by views
        $topNews = $this->newsRepository->findTopByViews(3);
        $this->assertCount(3, $topNews);
        $this->assertEquals(400, $topNews[0]->getViewCount());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
} 
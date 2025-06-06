<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class NewsFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // Create 50 news articles
        for ($i = 0; $i < 50; $i++) {
            $news = new News();
            $news->setTitle($this->faker->sentence(rand(3, 6), true));
            $news->setShortDescription($this->faker->paragraph(rand(1, 2)));
            $news->setContent($this->faker->paragraphs(rand(3, 6), true));
            $news->setInsertDate($this->faker->dateTimeBetween('-1 year', 'now'));
            $news->setViewCount($this->faker->numberBetween(0, 1000));
            $news->setPublished(true); // All published (matches spec)

            // Add 1-3 random categories
            $this->addRandomCategories($manager, $news);

            $manager->persist($news);
            $this->addReference('news_' . $i, $news);
        }

        $manager->flush();
    }

    private function addRandomCategories(ObjectManager $manager, News $news): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        
        if (empty($categories)) {
            return;
        }
        
        $numCategories = rand(1, min(3, count($categories)));
        $selectedCategories = array_rand($categories, $numCategories);
        
        // Handle case where array_rand returns single value for count=1
        if (!is_array($selectedCategories)) {
            $selectedCategories = [$selectedCategories];
        }
        
        foreach ($selectedCategories as $index) {
            $news->addCategory($categories[$index]);
        }
    }
} 
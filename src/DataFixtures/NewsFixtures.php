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
        $categories = $manager->getRepository(Category::class)->findAll();
        
        if (empty($categories)) {
            return;
        }

        for ($i = 1; $i <= 50; $i++) {
            $news = new News();
            $news->setTitle($this->faker->sentence(6, true));
            $news->setShortDescription($this->faker->paragraph(2, true));
            $news->setContent($this->faker->paragraphs(5, true));
            $news->setInsertDate($this->faker->dateTimeBetween('-6 months'));
            $news->setViewCount($this->faker->numberBetween(0, 1000));

            $categoryCount = $this->faker->numberBetween(1, 3);
            $selectedCategories = (array) $this->faker->randomElements($categories, $categoryCount);
            
            foreach ($selectedCategories as $category) {
            $news->addCategory($category);
            }

            if (count($selectedCategories) === 1 && is_array($selectedCategories[0])) {
                $news->getCategories()->clear();
                $news->addCategory($selectedCategories[0]);
            }

            $manager->persist($news);
        }

        $manager->flush();
    }
} 
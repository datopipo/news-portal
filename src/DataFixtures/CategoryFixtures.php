<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private array $categories = [
        [
            'title' => 'Technology',
            'description' => 'Latest news and updates from the world of technology, including gadgets, software, and digital trends.'
        ],
        [
            'title' => 'Sports',
            'description' => 'Coverage of various sports events, athlete profiles, and sports-related news from around the world.'
        ],
        [
            'title' => 'Business',
            'description' => 'Business news, market updates, company profiles, and economic trends affecting global markets.'
        ],
        [
            'title' => 'Entertainment',
            'description' => 'News about movies, music, television, celebrities, and the entertainment industry.'
        ],
        [
            'title' => 'Science',
            'description' => 'Scientific discoveries, research breakthroughs, and developments in various scientific fields.'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $index => $categoryData) {
            $category = new Category();
            $category->setTitle($categoryData['title']);
            $category->setDescription($categoryData['description']);

            $manager->persist($category);
            $this->addReference('category_' . $index, $category);
        }

        $manager->flush();
    }
} 
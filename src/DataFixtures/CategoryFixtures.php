<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private array $categories = [
        'Technology',
        'Sports', 
        'Business',
        'Entertainment',
        'Science',
        'Politics',
        'Health',
        'Travel',
        'Food',
        'Fashion'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $index => $categoryTitle) {
            $category = new Category();
            $category->setTitle($categoryTitle);

            $manager->persist($category);
            $this->addReference('category_' . $index, $category);
        }

        $manager->flush();
    }
} 
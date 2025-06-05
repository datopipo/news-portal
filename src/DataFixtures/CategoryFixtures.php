<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Technology' => 'Latest technology news and updates',
            'Sports' => 'Sports news and events coverage',
            'Politics' => 'Political news and analysis',
            'Business' => 'Business and economic news'
        ];

        foreach ($categories as $title => $description) {
            $category = new Category();
            $category->setTitle($title);
            $category->setDescription($description);
            
            $manager->persist($category);
            
            // Add reference for NewsFixtures
            $this->addReference('category_' . $title, $category);
        }

        $manager->flush();
    }
} 
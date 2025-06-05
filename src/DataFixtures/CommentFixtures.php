<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // Create 200 comments
        for ($i = 0; $i < 200; $i++) {
            $comment = new Comment();
            $comment->setAuthorName($this->faker->name());
            $comment->setEmail($this->faker->email());
            $comment->setContent($this->faker->paragraph(2));
            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 year', 'now'));

            // Assign to a random news article
            $newsRef = 'news_' . rand(0, 49); // We have 50 news articles
            if ($this->hasReference($newsRef)) {
                $news = $this->getReference($newsRef);
                $comment->setNews($news);
            }

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NewsFixtures::class,
        ];
    }
} 
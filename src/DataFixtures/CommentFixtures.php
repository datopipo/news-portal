<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\News;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $newsArticles = $manager->getRepository(News::class)->findAll();
        
        if (empty($newsArticles)) {
            return;
        }

        for ($i = 0; $i < 200; $i++) {
            $comment = new Comment();
            $comment->setAuthorName($this->faker->name());
            $comment->setEmail($this->faker->email());
            $comment->setContent($this->faker->paragraph(3, true));
            $comment->setCreatedAt($this->faker->dateTimeBetween('-6 months'));
            
            $randomNews = $this->faker->randomElement($newsArticles);
            $comment->setNews($randomNews);
            
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
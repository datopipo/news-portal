<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $comments = [
            [
                'news' => 'AI Breakthrough in Natural Language Processing',
                'author' => 'John Smith',
                'email' => 'john.smith@example.com',
                'content' => 'This is a fascinating development! I can\'t wait to see how this technology evolves.',
                'created_at' => new \DateTime('-2 days')
            ],
            [
                'news' => 'AI Breakthrough in Natural Language Processing',
                'author' => 'Sarah Johnson',
                'email' => 'sarah.j@example.com',
                'content' => 'The implications for natural language understanding are enormous. Great article!',
                'created_at' => new \DateTime('-1 day')
            ],
            [
                'news' => 'World Cup Final: Historic Victory',
                'author' => 'Mike Wilson',
                'email' => 'mike.w@example.com',
                'content' => 'What an incredible match! The underdogs truly deserved this victory.',
                'created_at' => new \DateTime('-3 days')
            ],
            [
                'news' => 'Global Climate Summit Reaches Historic Agreement',
                'author' => 'Emma Davis',
                'email' => 'emma.d@example.com',
                'content' => 'Finally, some real progress on climate action. Let\'s hope countries follow through.',
                'created_at' => new \DateTime('-4 days')
            ],
            [
                'news' => 'Tech Giant Announces Revolutionary Product',
                'author' => 'Alex Brown',
                'email' => 'alex.b@example.com',
                'content' => 'The integration of AI with intuitive design is exactly what we needed.',
                'created_at' => new \DateTime('-2 days')
            ]
        ];

        foreach ($comments as $commentData) {
            $comment = new Comment();
            $comment->setAuthorName($commentData['author']);
            $comment->setEmail($commentData['email']);
            $comment->setContent($commentData['content']);
            $comment->setCreatedAt($commentData['created_at']);

            // Find the news article by title
            $news = $manager->getRepository(News::class)->findOneBy(['title' => $commentData['news']]);
            if ($news) {
                $comment->setNews($news);
                $manager->persist($comment);
            }
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
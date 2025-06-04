<?php

namespace App\DataFixtures;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $newsData = [
            [
                'title' => 'AI Breakthrough in Natural Language Processing',
                'shortDescription' => 'Researchers achieve significant progress in understanding human language patterns.',
                'content' => 'A team of researchers has made a breakthrough in natural language processing, achieving unprecedented accuracy in understanding and generating human language. The new model shows remarkable capabilities in context understanding and nuanced communication.',
                'picture' => 'ai-breakthrough.jpg',
                'category' => 'Technology',
                'viewCount' => 1250
            ],
            [
                'title' => 'World Cup Final: Historic Victory',
                'shortDescription' => 'Underdog team makes history with stunning World Cup victory.',
                'content' => 'In a match that will be remembered for generations, the underdog team secured a historic victory in the World Cup final. The team\'s remarkable journey and final triumph have inspired millions around the globe.',
                'picture' => 'world-cup.jpg',
                'category' => 'Sports',
                'viewCount' => 3420
            ],
            [
                'title' => 'Global Climate Summit Reaches Historic Agreement',
                'shortDescription' => 'World leaders commit to ambitious climate action plan.',
                'content' => 'In a landmark decision, world leaders have agreed on an ambitious plan to combat climate change. The agreement includes unprecedented commitments to reduce carbon emissions and support developing nations.',
                'picture' => 'climate-summit.jpg',
                'category' => 'Politics',
                'viewCount' => 1890
            ],
            [
                'title' => 'Tech Giant Announces Revolutionary Product',
                'shortDescription' => 'New technology promises to transform daily life.',
                'content' => 'A leading technology company has unveiled its latest innovation, promising to revolutionize how we interact with technology. The product combines cutting-edge AI with intuitive design.',
                'picture' => 'tech-product.jpg',
                'category' => 'Technology',
                'viewCount' => 2760
            ],
            [
                'title' => 'Economic Recovery Shows Strong Signs',
                'shortDescription' => 'Global economy demonstrates robust growth indicators.',
                'content' => 'Recent economic data shows promising signs of recovery, with key indicators pointing to sustained growth. Experts predict continued positive trends in the coming months.',
                'picture' => 'economy.jpg',
                'category' => 'Business',
                'viewCount' => 1530
            ]
        ];

        foreach ($newsData as $data) {
            $news = new News();
            $news->setTitle($data['title']);
            $news->setShortDescription($data['shortDescription']);
            $news->setContent($data['content']);
            $news->setPicture($data['picture']);
            $news->setViewCount($data['viewCount']);
            $news->setInsertDate(new \DateTime());
            
            // Get the category reference and add it to the news
            $category = $this->getReference('category_' . $data['category'], Category::class);
            $news->addCategory($category);

            $manager->persist($news);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
} 
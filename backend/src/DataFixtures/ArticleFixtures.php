<?php
declare(strict_types=1);
namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $article = (new Article())->setArticleNumber('A-101BOL999')
            ->setname('Bolts')
            ->setUnitPrice('0.50');

        $this->addReference('article-A-101BOL999', $article);
        $manager->persist($article);

        $article = (new Article())->setArticleNumber('A-102WASH999')
            ->setname('Washers')
            ->setUnitPrice('0.10');
        $this->addReference('article-A-102WASH999', $article);
        $manager->persist($article);

        $article = (new Article())->setArticleNumber('A-103NUT999')
            ->setname('Nuts')
            ->setUnitPrice('0.90');
        $this->addReference('article-A-103NUT999', $article);
        $manager->persist($article);


        $manager->flush();
    }
}

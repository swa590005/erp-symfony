<?php
declare(strict_types=1);
namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderLine;
use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PurchaseOrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $article = $this->getReference('article-A-101BOL999',Article::class);
        $line1 = (new PurchaseOrderLine())->setArticle($article)->setQuantity(10)->setUnitPrice($article->getUnitPrice());
        $article = $this->getReference('article-A-102WASH999',Article::class);
        $line2 = (new PurchaseOrderLine())->setArticle($article)->setQuantity(20)->setUnitPrice($article->getUnitPrice());
        $article = $this->getReference('article-A-103NUT999',Article::class);
        $line3 = (new PurchaseOrderLine())->setArticle($article)->setQuantity(15)->setUnitPrice($article->getUnitPrice());
        $supplier = $this->getReference('supplier-S-101',Supplier::class);

        $purchaseOrder = (new PurchaseOrder())->setSupplier($supplier)
        ->setOrderDate(new \DateTimeImmutable())
        ->setOrderNumber('PO-101')
        ->addOrderLine($line1)
        ->addOrderLine($line2)
        ->addOrderLine($line3);
        $manager->persist($purchaseOrder);
        $manager->flush();
    }

    // define the order in which fixtures will be loaded
    public function getDependencies(): array
    {
        return [
            ArticleFixtures::class,
            SupplierFixtures::class
        ];
    }
}

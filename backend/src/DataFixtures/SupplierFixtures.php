<?php
declare(strict_types=1);
namespace App\DataFixtures;

use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SupplierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

       $supplier = (new Supplier())->setSupplierName('Supplier1')
           ->setSupplierNumber('S-101')
           ->setEmailAddress('sup1@erp.com')
           ->setAddress('First Street 123');
       $manager->persist($supplier);
       $supplier = (new Supplier())->setSupplierName('Supplier2')
           ->setSupplierNumber('S-102')
           ->setEmailAddress('sup2@erp.com')
           ->setAddress('Second Street 234')
           ->setPhoneNumber('0123456789');
       $manager->persist($supplier);
       $supplier = (new Supplier())->setSupplierName('Supplier3')
           ->setSupplierNumber('S-103')
           ->setEmailAddress('sup3@erp.com')
           ->setAddress('Third Street 345');
       $manager->persist($supplier);
       $manager->flush();
    }
}

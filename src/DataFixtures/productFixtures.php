<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class productFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName("phone");
        $product->setDescription("best phone you have ever seen");
        $product->setQuantity(100);
        $product->setPrice(999.99);
        $product->setCategory($this->getReference('category'));
        $manager->persist($product);

        $product2 = new Product();
        $product2->setName("notebook");
        $product2->setDescription("best notebook you have ever seen");
        $product2->setQuantity(50);
        $product2->setPrice(1999.99);
        $product2->setCategory($this->getReference('category'));
        $manager->persist($product2);



        $manager->flush();
    }
}

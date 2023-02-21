<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category= new Category();
        $category->setName('Electronic');

        $manager->persist($category);
        $manager->flush();

        $this->addReference('category',$category);

    }
}

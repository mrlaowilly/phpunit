<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create("fr_FR");
        for($i=0; $i<5; $i++){
            $category = new Category();
            $category->setName($faker->word());
            $manager->persist($category);
            $this->addReference("CAT".$i, $category);
        }

        $manager->flush();
    }
}

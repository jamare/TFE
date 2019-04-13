<?php

namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($k=0;$k<10;$k++){
            $category = new Category();
            $category->setName($faker->jobTitle);

            $this->setReference("category_".$k, $category);

            $manager->persist($category);
        }

        $manager->flush();
    }

}

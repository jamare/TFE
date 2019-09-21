<?php

namespace App\DataFixtures;


use App\Entity\Province;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ProvinceFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($k=0;$k<10;$k++){
            $province = new Province();
            $province->setName($faker->country);

            $this->setReference("province_".$k, $province);

            $manager->persist($province);
        }

        $manager->flush();
    }

}

<?php

namespace App\DataFixtures;


use App\Entity\PostalCode;
use App\Entity\Locality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LocalityFixtures extends Fixture
{

    const AMOUNT_CP = 6;
    const AMOUNT_LOCALITY = 3;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i=1;$i<= self::AMOUNT_CP;$i++) {
            $postalCode = new PostalCode();
            $postalCode->setCp($faker->numberBetween(4000,5000));
            $this->setReference('cp_'.$i,$postalCode);
            $manager->persist($postalCode);

            // création de localité
            for ($j = 1; $j <= self::AMOUNT_LOCALITY; $j++) {
                $locality = new Locality();
                $locality->setLocality($faker->city);
                $this->setReference('locality_'.$i.'_'.$j,$locality);
                $manager->persist($locality);
            }
        }

        $manager->flush();
    }

}

<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Demand;
use App\Entity\Provider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    const AMOUNT_CP = 6;
    const AMOUNT_LOCALITY = 3;

    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');

        // Création des prestataires
        for($i=1;$i<=10;$i++){
            $provider = new Provider();
            $provider->setName($faker->company);
            $provider->setAddress($faker->streetAddress);
            $randCP = rand(1,self::AMOUNT_CP);
            $provider->setPostalCode($this->getReference('cp_'.$randCP));
            $provider->setLocality($this->getReference('locality_'.$randCP.'_'.rand(1,self::AMOUNT_LOCALITY)));
            $provider->setPhone($faker->phoneNumber);
            $provider->setEmail($faker->email);
            $provider->setTva($faker->vat);
            $provider->setWeb($faker->url);
            $provider->addService($this->getReference("category_".rand(1,8)));
            $provider->addService($this->getReference("category_".rand(1,8)));
            $provider->setRegistration($faker->dateTimeBetween('-365 days', '-1 days'));
            $provider->setPassword('password');
            $provider->setBanished(false);

            $manager->persist($provider);
        }

        // Création des utilisateurs du site
        for($j=1;$j<=10;$j++){
            $customer = new Customer();
            $customer->setName($faker->lastName);
            $customer->setFirstName($faker->firstName);
            $customer->setAddress($faker->streetAddress);
            $randCP = rand(1,self::AMOUNT_CP);
            $customer->setPostalCode($this->getReference('cp_'.$randCP));
            $customer->setLocality($this->getReference('locality_'.$randCP.'_'.rand(1,self::AMOUNT_LOCALITY)));
            $customer->setPhone($faker->phoneNumber);
            $customer->setEmail($faker->email);
            $customer->setRegistration($faker->dateTimeBetween('-365 days', '-1 days'));
            $customer->setPassword('password');
            $customer->setBanished(false);
            //$customer->addDemand($this->getReference('demand_'.rand(1,10)));

            $demand = new Demand();
            $demand->setTitle($faker->sentence);
            $description = '<p>' . join($faker->paragraphs(3), '</p><p>') . '</p>';
            $demand->setDescription($description);
            $demand->setCustomer($customer);

            $manager->persist($demand);
            $manager->persist($customer);
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LocalityFixtures::class,
            CategoryFixtures::class,

        );

    }
}

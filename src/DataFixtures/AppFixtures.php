<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Demand;
use App\Entity\Provider;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    const AMOUNT_CP = 6;
    const AMOUNT_LOCALITY = 3;

    public function load(ObjectManager $manager)
    {

        $faker= Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new Customer();
        $randCP = rand(1,self::AMOUNT_CP);
        $adminUser->setName('Jamar')
                  ->setFirstName('Eric')
                  ->setAddress('rue lieutenant simon 5')
                  ->setPostalCode($this->getReference('cp_'.$randCP))
                  ->setLocality($this->getReference('locality_'.$randCP.'_'.rand(1,self::AMOUNT_LOCALITY)))
                  ->setEmail('the_real_magnatt@hotmail.com')
                  ->setPhone('0476777275')
                  ->setPassword($this->encoder->encodePassword($adminUser, 'password'))
                  ->setRegistration($faker->dateTimeBetween('-365 days', '-1 days'))
                  ->setBanished(false)
                  ->addUserRole($adminRole);

        $manager->persist($adminUser);



        // Création des prestataires
        for($i=1;$i<=10;$i++){


            $provider = new Provider();

            $password = $this->encoder->encodePassword($provider, 'password');

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
            $provider->setPassword($password);
            $provider->setBanished(false);

            $manager->persist($provider);
        }

        // Création des utilisateurs du site
        for($j=1;$j<=10;$j++){
            $customer = new Customer();

            $password = $this->encoder->encodePassword($customer, 'password');
            $customer->setName($faker->lastName);
            $customer->setFirstName($faker->firstName);
            $customer->setAddress($faker->streetAddress);
            $randCP = rand(1,self::AMOUNT_CP);
            $customer->setPostalCode($this->getReference('cp_'.$randCP));
            $customer->setLocality($this->getReference('locality_'.$randCP.'_'.rand(1,self::AMOUNT_LOCALITY)));
            $customer->setPhone($faker->phoneNumber);
            $customer->setEmail($faker->email);
            $customer->setRegistration($faker->dateTimeBetween('-365 days', '-1 days'));
            $customer->setPassword($password);
            $customer->setBanished(false);


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

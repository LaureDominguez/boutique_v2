<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Product;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');

        for ($i=0; $i<10; $i++){
            $product = new Product();
            $product
            ->setCategory(null)
            ->setName($faker->word())
            ->setDescription($faker->realText($faker->numberBetween(25, 100)))
            ->setPrice($faker->randomDigitNotNull());

            $manager->persist($product);
        }

        $manager->flush();
    }
}

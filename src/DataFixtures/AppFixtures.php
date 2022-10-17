<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Product;
use Faker\Factory;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Repository\CategoryRepository;

class AppFixtures extends Fixture
{
    //added "CategoryRepository"
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    ////////////////////////////

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        //added "$categories"
        $categories = $this->categoryRepository->findAll();
        /////////////////////

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

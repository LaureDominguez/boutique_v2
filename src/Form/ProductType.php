<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//added "CategoryRepository" path
use App\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    //added "CategoryRepository"
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    ////////////////////////////

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //added "$categories"
        $categories = $this->categoryRepository->findAll();
        /////////////////////

        $builder
            ->add('category', ChoiceType::class, [
                "choices"       => $categories,
                "choice_value"  => "id",
                "choice_label"  => "name"
            ])
            ->add('name')
            ->add('description')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

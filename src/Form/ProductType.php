<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//added "CategoryRepository" path
use App\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//added "GalleryRepository" path
use App\Repository\GalleryRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductType extends AbstractType
{
    //added "CategoryRepository"
    private $categoryRepository;

    //added "GalleryRepository"
    private $galleryRepository;

    public function __construct(CategoryRepository $categoryRepository, GalleryRepository $galleryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->galleryRepository = $galleryRepository;
    }
    ////////////////////////////

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //added "$categories"
        $categories = $this->categoryRepository->findAll();
        /////////////////////

        //added "$galleries"
        $galleries = $this->galleryRepository->findAll();
        /////////////////////

        $builder
            ->add('category', ChoiceType::class, [
            "choices"       => $categories,
            "choice_value"  => "id",
            "choice_label"  => "name",
            "attr" => ["class" => "form-select"],
            "label" => "Catégorie"
        ])
            ->add('name', null, [
            "attr" => ["class" => "form-control"],
            "label" => "Nom",
            "label_attr" => ["class" => "form-label"]
        ])
            ->add('description', null, [
            "attr" => ["class" => "form-control"],
            "label" => "Description",
            "label_attr" => ["class" => "form-label"]
        ])
            ->add('price', null, [
            "attr" => ["class" => "form-control"],
            "label" => "Prix",
            "label_attr" => ["class" => "form-label"]
        ])
            //->add("gallery")
            ->add('gallery', FileType::class, [
            "attr" => ["class" => "form-control"],
            'label' => "Photo",
            //'multiple' => true,
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new Image([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/tiff'
                    ],
                    'mimeTypesMessage' => "Merci d'utiliser un format valide (jpeg, png, tiff)",
                ])
            ]
        ])
        // ->add('saveAndAdd', SubmitType::class, ['label' => 'Ajouter'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

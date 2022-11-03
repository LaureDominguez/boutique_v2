<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use PhpParser\Builder\Property;

class HomeController extends AbstractController
{
    private ?CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $this->checkCart();
        return $this->render('home/index.html.twig', [
            'display_cart' => $this->checkCart(),
        ]);
    }

    #[Route('/shop_admin', name: 'app_shop_admin')]
    public function shop_admin(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        return $this->render('shop_admin/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $productRepository->findAll(),
            'display_cart' => false,
        ]);
    }

    #[Route('/shop_user', name: 'app_shop_user')]
    public function shop_user(ProductRepository $productRepository): Response
    {
        return $this->render('shop_user/index.html.twig', [
            'products' => $productRepository->findAll(),
            'display_cart' => $this->checkCart(),
        ]);
    }

    #[Route('/registred/basket', name: 'app_basket', methods: ['GET'])]
    public function show(CartRepository $cartRepository, ProductRepository $productRepository): Response
    {
        return $this->render('basket/show.html.twig', [
            'cart' => $cartRepository->findBy(['user' => $this->getUser()]),
            'display_cart' => $this->checkCart(),
            // 'products' => $productRepository->findBy(['id']),
            // 'basket' => $cartRepository->findBy(['product'])
        ]);
    }

    private function checkCart()
    {
        $user = $this->getUser();
        if ($user !== null){
            $checkCart = $this->cartRepository->findBy(['user' => $this->getUser()]);
            if (!empty($checkCart)){
                return true;
            }
        }
        return false;
    }
}
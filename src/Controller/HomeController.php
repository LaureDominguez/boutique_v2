<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
// use App\Entity\Cart;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/shop_admin', name: 'app_shop_admin')]
    public function shop_admin(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        return $this->render('shop_admin/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/shop_user', name: 'app_shop_user')]
    public function shop_user(ProductRepository $productRepository): Response
    {
        return $this->render('shop_user/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/registred/basket', name: 'app_basket', methods: ['GET'])]
    public function show(CartRepository $cartRepository, ProductRepository $productRepository): Response
    {
        return $this->render('basket/show.html.twig', [
            'cart' => $cartRepository->findBy(['user'=>$this->getUser()]),
            // 'products' => $productRepository->findBy(['id']),
            // 'basket' => $cartRepository->findBy(['product'])
        ]);
    }
}
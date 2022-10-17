<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

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
}

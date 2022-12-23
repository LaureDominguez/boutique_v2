<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Form\CartType;
use App\Repository\CartRepository;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/registred/cart')]
class CartController extends AbstractController
{

    private ?CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function checkCart()
    {
        $user = $this->getUser();
        if ($user !== null) {
            $checkCart = $this->cartRepository->findBy(['user' => $this->getUser()]);
            if (!empty($checkCart)) {
                return true;
            }
        }
        return false;
    }

    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartRepository $cartRepository): Response
    {
        return $this->render('cart/index.html.twig', [
            'carts' => $cartRepository->findAll(),
            'display_cart' => false,
        ]);
    }

    #[Route('/new/{id}', name: 'app_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CartRepository $cartRepository, Product $product): Response
    {
        $cart = new Cart();
        $cart->setQuantity($request->request->get('quantity'));
        $cart->setUser($this->getUser());
        $cart->setProduct($product);

        $cartRepository->save($cart, true);

        return $this->redirectToRoute('app_product_show', [
            "id"=>$product->getId(),
            'display_cart' => $this->checkCart(),
        ]);
    }


    #[Route('/{id}', name: 'app_cart_show', methods: ['GET'])]
    public function show(Cart $cart, User $user, Product $product, ProductRepository $productRepository): Response
    {
        return $this->render('cart/show.html.twig', [
            'product' => $product,
            'cart' => $cart,
            'user' => $user->getId(),
            'products' => $productRepository->findBy([
                "product_id" => $product
            ]),

            'display_cart' => false,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cart $cart, CartRepository $cartRepository): Response
    {
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cartRepository->save($cart, true);

            return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cart/edit.html.twig', [
            'cart' => $cart,
            'form' => $form,
            'display_cart' => false,
        ]);
    }

    #[Route('/{id}', name: 'app_cart_delete', methods: ['POST'])]
    public function delete(Request $request, Cart $cart, CartRepository $cartRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $cartRepository->remove($cart, true);
        }

        return $this->redirectToRoute('app_cart_index', ['display_cart' => false,], Response::HTTP_SEE_OTHER);
    }
}

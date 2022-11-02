<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Form\CartType;
use App\Repository\CartRepository;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartRepository $cartRepository): Response
    {
        return $this->render('cart/index.html.twig', [
            'carts' => $cartRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CartRepository $cartRepository, Product $product): Response
    {
        $cart = new Cart();
        // $form = $this->createForm(CartType::class, $cart);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

            $cart->setQuantity(1);
            $cart->setUser($this->getUser());
            $cart->setProduct($product);

            $cartRepository->save($cart, true);

            // change targetPath
            // $targetPath = $this->getTargetPath();
            // return new RedirectResponse($targetPath);

            return $this->redirectToRoute('app_product_show', ["id"=>$product->getId()]);
        // }

        // return $this->renderForm('cart/new.html.twig', [
        //     'cart' => $cart,
        //     'form' => $form,
        //])
        ;
    }


    // public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    // {
    //     if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
    //         //retourne vers la page sur laquelle l'utilisateur a besoin de s'identifier
    //         return new RedirectResponse($targetPath);
    //     }
    //     //sinon renvois sur la home en étant loggé
    //     return new RedirectResponse($this->urlGenerator->generate('app_home'));
    //     //et sinon affiche une erreur
    //     throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
    // }


################## old new #####################
    // #[Route('/new', name: 'app_cart_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, CartRepository $cartRepository): Response
    // {
    //     $cart = new Cart();
    //     $form = $this->createForm(CartType::class, $cart);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()
    //     ) {
    //         $cartRepository->save($cart, true);

    //         return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('cart/new.html.twig', [
    //         'cart' => $cart,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_cart_show', methods: ['GET'])]
    public function show(Cart $cart): Response
    {
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
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
        ]);
    }

    #[Route('/{id}', name: 'app_cart_delete', methods: ['POST'])]
    public function delete(Request $request, Cart $cart, CartRepository $cartRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $cartRepository->remove($cart, true);
        }

        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }
}

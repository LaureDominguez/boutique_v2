<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Form\PasswordEditType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//added "UserPasswordHasherInterface" path
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    
    public CartController $cartController;
    public function __construct(CartController $cartController)
    {
        $this->cartController = $cartController;
    }
    public function checkCart()
    {
        $this->checkCart = $this->cartController->checkCart();
    }

    // #[Route('/user/list', name: 'app_user_index', methods: ['GET'])]
    // public function index(UserRepository $userRepository): Response
    // {
    //     return $this->render('user/index.html.twig', [
    //         'users' => $userRepository->findAll(),
    //         'display_cart' => false,
    //     ]);
    // }

    #[Route('/register', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passHasher->hashPassword($user, $user->getPassword()));
            $userRepository->save($user, true);

            $this->addFlash('success', 'Le compte a bien été créé !');
            return $this->redirectToRoute('app_login');
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'display_cart' => false,
        ]);
    }

    #[Route('/registred/{id}/editPass', name: 'app_password_edit', methods: ['GET', 'POST'])]
    public function passEdit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passHasher): Response
    {
        $form = $this->createForm(PasswordEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passHasher->hashPassword($user, $user->getPassword()));
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit-pass.html.twig', [
            'user' => $user,
            'form' => $form,
            'display_cart' => false,
        ]);
    }

    #[Route('/registred/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'display_cart' => $this->checkCart(),
        ]);
    }

    #[Route('/registred/{id}/editProfil', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function userEdit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_show', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'display_cart' => false,
        ]);
    }
    
    #[Route('/registred/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->container->get('security.token_storage')->setToken(null);
            $userRepository->remove($user, true);
        }

        $this->addFlash('deleted', 'Votre compte a bien été supprimé.');
        return $this->redirectToRoute('app_home', ['display_cart' => false,], Response::HTTP_SEE_OTHER);
    }
}

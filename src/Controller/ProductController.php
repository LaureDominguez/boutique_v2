<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GalleryRepository;

#[Route('/product')]
class ProductController extends AbstractController
{
///////// Index /////////////////

    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

///////// New /////////

    #[Route('admin/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository$productRepository, GalleryRepository $galleryRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);
            $images = $form->get('gallery')->getData();

            $fichier = md5(uniqid()) . '-' . uniqid() . '.' . $images->guessExtension();
            $images->move(
                $this->getParameter('kernel.project_dir') . ('/public/uploads/images_directory'),
                $fichier
            );

            // On boucle sur les images
            // foreach ($images as $image) {
            //     // On génère un nouveau nom de fichier
            //     $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            //     // On copie le fichier dans le dossier uploads
            //     $image->move(
            //         $this->getParameter('./public/uploads/images_directory'),
            //         $fichier
            //     );

            // On crée l'image dans la base de données
            $img = new Gallery();
            $img->setPicture($fichier);
            $galleryRepository->save($img, true);
            $product->addGallery($img);
            // }

            $productRepository->save($product, true);
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

////////////// Show ////////////

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product, GalleryRepository $galleryRepository): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
            
            'gallery' => $galleryRepository->findBy([
                "product" => $product,
            ]),
        ]);
    }

///////////// Edit ////////////////////

    #[Route('admin/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository, GalleryRepository $galleryRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        // if ($form->getClickedButton() && 'saveAndAdd' === $form->getClickedButton()->getName()) {
        //     // ...
        // }

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('gallery')->getData();

            $fichier = md5(uniqid()) . '-' . uniqid() . '.' . $images->guessExtension();
            $images->move(
                $this-> getParameter('kernel.project_dir') . ('/public/uploads/images_directory'),
                $fichier
            );

            // On boucle sur les images
            // foreach ($images as $image) {
            //     // On génère un nouveau nom de fichier
            //     $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            //     // On copie le fichier dans le dossier uploads
            //     $image->move(
            //         $this->getParameter('./public/uploads/images_directory'),
            //         $fichier
            //     );

                // On crée l'image dans la base de données
                $img = new Gallery();
                $img->setPicture($fichier);
                $galleryRepository->save($img, true);
                $product->addGallery($img);
            // }

            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

///////////////// Delete ///////////////

    #[Route('admin/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}

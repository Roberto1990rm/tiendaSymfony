<?php

// src/Controller/ProductController.php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     */
 
public function index(EntityManagerInterface $entityManager): Response
{
    $products = $entityManager->getRepository(Product::class)->findAll();

    return $this->render('product/index.html.twig', [
        'products' => $products,
    ]);
}

public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageFile')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Manejar la excepción si algo sucede durante la carga del archivo
            }

            $product->setImg($newFilename);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_index');
    }

    return $this->render('product/create.html.twig', [
        'form' => $form->createView(),
    ]);
}
}

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
 
     public function index(EntityManagerInterface $entityManager, Request $request): Response
     {
         $limite = 10; // Número de productos por página
         $pagina = max(1, $request->query->getInt('page', 1)); // Obtener el número de página de la URL, 1 por defecto
         $offset = ($pagina - 1) * $limite;
     
         $totalProductos = $entityManager->getRepository(Product::class)->count([]);
         $products = $entityManager->getRepository(Product::class)
             ->findBy([], ['id' => 'ASC'], $limite, $offset);
     
         return $this->render('product/index.html.twig', [
             'products' => $products,
             'totalProductos' => $totalProductos,
             'paginaActual' => $pagina,
             'totalPaginas' => ceil($totalProductos / $limite)
         ]);
     }

#[Route('/create', name: 'product_create')]
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
        
        // Setear user_id a 0
        $product->setUserId(0);

        $entityManager->persist($product);
        $entityManager->flush();

        $this->addFlash('success', 'Producto creado exitosamente.');

        return $this->redirectToRoute('product_index');
    }

    return $this->render('product/create.html.twig', [
        'form' => $form->createView(),
    ]);
}

}

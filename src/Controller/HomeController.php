<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], [], 3);


        // $productRepository = $em->getRepository(Product::class);

        // $product = $productRepository->find(3);

        // $product->setPrice(2500);

        // $em->flush();

        //$product = new Product;

        // $product
        //     ->setName('Table en métal')
        //     ->setPrice(3000)
        //     ->setSlug('table-en-metal');

        // $em->persist($product);
        // $em->flush();

        return $this->render('home.html.twig', [
            'products' => $products
        ]);
    }
}

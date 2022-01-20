<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category", priority=-1)
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            //throw new NotFoundHttpException("La catégorie demandée n'existe pas");
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */
    public function show($slug, ProductRepository $productRepository)
    {
        //dd($urlGenerator->generate('homepage'));

        // dd($urlGenerator->generate('product_category', [
        //     'slug' => $slug
        // ]));

        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$product) {
            throw $this->createNotFoundException("Le produit demandée n'existe pas");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product, [
            "validation_groups" => ["Default", "with-price"]
        ]);
        // $builder = $factory->createBuilder(ProductType::class);

        // $options = [];

        // foreach ($categoryRepository->findAll() as $category) {
        //     $options[$category->getName()] = $category->getId();
        // }

        //$form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            // $product = new Product;
            // $product->setName($data['name'])
            //     ->setShortDescription($data['shortDescription'])
            //     ->setPrice($data->price)
            //     ->setCategory($data->category);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("admin/product/{id}/edit", name="product_edit")
     */
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        // $product = new Product;
        // $product->setName("Nicolas");

        // $resultat = $validator->validate($product);

        // if ($resultat->count() > 0) {
        //     dd("Il y a des erreurs : ", $resultat);
        // } else {
        //     dd("Tout vas bien");
        // }

        // $client = [
        //     'nom' => "",
        //     'prenom' => "Nicolas",
        //     'pc' => [
        //         'marque' => '',
        //         'couleur' => 'noir'
        //     ]
        // ];

        // $collection = new Collection([
        //     'nom' => new NotBlank(['message' => "Le nom ne doit pas être vide !"]),
        //     'prenom' => [
        //         new NotBlank(['message' => "Le prenom ne doit pas être vide"]),
        //         new Length(['min' => 3, 'minMessage' => "Le prenom ne doit pas faire moins de 34 caractères "])
        //     ],
        //     'pc' => new Collection([
        //         'marque' => new NotBlank(['message' => "La marque du pc est obligatoire"]),
        //         'couleur' => new NotBlank(['message' => 'La couleur du pc est obligatoire'])
        //     ])
        // ]);

        // $resultat = $validator->validate($client, $collection);



        // $age = 200;

        // $resultat = $validator->validate($age, [
        //     new LessThan([
        //         'value' => 120,
        //         'message' => "L'âge doit être inférieur à {{ compared_value }} mais vous avez donné {{ value }}"
        //     ]),
        //     new GreaterThan([
        //         'value' => 0,
        //         'message' => "L'age doit être supérieur à 0"
        //     ])
        // ]);

        // if ($resultat->count() > 0) {
        //     dd("Il y a des erreurs : ", $resultat);
        // } else {
        //     dd("Tout vas bien");
        // }

        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product, [
            "validation_groups" => ["Default", "with-price"]
        ]);

        //$form->setData($product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            //$product = $form->getData();

            // $url = $urlGenerator->generate('product_show', [
            //     'category_slug' => $product->getCategory()->getSlug(),
            //     'slug' => $product->getSlug()
            // ]);


            // $response = new RedirectResponse($url);

            // return $response;
            // $response->headers->set('Location', $url);
            // $response->setStatusCode(302);

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }
}

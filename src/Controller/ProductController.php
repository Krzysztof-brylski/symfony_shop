<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    protected CategoryRepository $categoryRepo;
    protected ProductRepository $productRepo;
    public function __construct(CategoryRepository $CategoryRepository, ProductRepository $productRepository){
        $this->categoryRepo=$CategoryRepository;
        $this->productRepo=$productRepository;
    }


    #[Route('/', name: 'product.index')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $this->productRepo->findAll(),
            'categories'=>$this->categoryRepo->findAll()
        ]);
    }

    #[Route('/product/{id}', name: 'product.show')]
    public function show($id): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $this->productRepo->find($id),
        ]);
    }

    #[Route('/product/create', name: 'product.create')]
    public function create(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/edit', name: 'product.edit')]
    public function edit(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/delete', name: 'product.delete',methods: ['DELETE'])]
    public function delete(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }



}

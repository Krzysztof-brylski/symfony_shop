<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    protected CategoryRepository $categoryRepo;
    protected ProductRepository $productRepo;
    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository){
        $this->categoryRepo=$categoryRepository;
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

    #[Route('/touch/product/{id}', name: 'product.create',defaults: ['id'=>null])]
    public function create(Request $request,$id): Response
    {
        $id == null ? $product= new Product() : $product= $this->productRepo->find($id);

        $form = $this->createForm(ProductType::class, $product);
        $data=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            if($image=$form->get('images')->getData()){
                try {
                    $path=FileService::saveFile($this->getParameter('storage_directory'),$image);
                    $product->setImages($path);
                }catch (FileException $exception){
                    return $this->json(['error'=>$exception->getMessage()],500);
                }
            }
            $this->productRepo->save($product,true);
            return $this->redirectToRoute('product.index');
        }
        if($id == null){
            return $this->render('product/create.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/delete/product/{id}', name: 'product.delete')]
    public function delete($id): Response
    {
        $this->productRepo->remove(
            $this->productRepo->find($id),
            true
        );

        return $this->redirectToRoute('product.index');
    }



}

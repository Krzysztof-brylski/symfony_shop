<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\FileService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    #[Route('/list/product', name: 'product.list')]
    #[isGranted('ROLE_ADMIN')]
    public function list(): Response
    {
        return $this->render('product/list.html.twig', [
            'products' => $this->productRepo->findAll(),
        ]);
    }


    #[Route('/product/{id}', name: 'product.show')]
    #[ParamConverter('product', options: ['mapping' => ['id' => 'id']])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/touch/product/{id}', name: 'product.touch',defaults: ['id'=>null], )]
    #[ParamConverter('product', options: ['mapping' => ['id' => 'id']],isOptional: true)]
    public function touch(Request $request,?Product $product): Response
    {
        $product == null ? $product= new Product() : null;

        $form = $this->createForm(ProductType::class, $product);
        $data=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            if($image=$form->get('images')->getData()){
                try {

                    $path=FileService::saveFile(
                        $this->getParameter('storage_directory'),
                        $image
                    );

                    $product->setImages($path);

                }catch (FileException $exception){
                    $this->addFlash('Error',$exception->getMessage());
                    return $this->redirectToRoute('product.list');
                }
            }
            $this->productRepo->save($product,true);
            $this->addFlash('Success','Product touched');
            return $this->redirectToRoute('product.list');
        }

        return $this->render('product/touch.html.twig', [
            'form' => $form->createView(),
            'acton'=>($product->getId() == null ? "create": "edit"),
        ]);

    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/delete/product/{id}', name: 'product.delete')]
    public function delete(Product $product): Response
    {
        $this->productRepo->remove(
            $product,
            true
        );
        $this->addFlash('Success','product deleted');
        return $this->redirectToRoute('product.index');
    }



}

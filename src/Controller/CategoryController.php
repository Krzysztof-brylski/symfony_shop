<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class CategoryController extends AbstractController
{
    private CategoryRepository $categoryRepo;

    public function __construct(CategoryRepository $categoryRepository){
        $this->categoryRepo=$categoryRepository;
    }

    #[Route('/category', name: 'category.index')]
    public function index(): Response
    {
        //todo pagination and eager loading
        return $this->render('category/index.html.twig', [
            'categories' => $this->categoryRepo->findAll(),
        ]);
    }

    #[Route('/touch/category/{id}', name: 'category.touch',defaults: ['id'=>null])]
    #[ParamConverter('category', options: ['mapping' => ['id' => 'id']],isOptional: true)]
    public function touch(Request $request,?Category $category): Response{

        $category==null ? $category= new Category() : null;
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $this->categoryRepo->save($category,true);
            $this->addFlash('Success','Category touched!');
            return $this->redirectToRoute('category.index');
        }

        return $this->render('category/touch.html.twig',[
            'form'=>$form->createView(),
            'acton'=>($category->getId() == null)?"create":"edit",
        ]);
    }

    #[Route('/delete/category/{id}', name: 'category.delete')]
    #[ParamConverter('category', options: ['mapping' => ['id' => 'id']])]
    public function show(Category $category): Response
    {
        if($category->getProducts()->count() == 0){
            $this->categoryRepo->remove($category,true);

            $this->addFlash('Success','Category deleted');
            return $this->redirectToRoute('category.index');
        }

        $this->addFlash('Error','This category is in use');
        return $this->redirectToRoute('category.index');

    }



}

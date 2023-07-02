<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carte')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category , CategoryRepository $categoryRepository): Response
    {

    $plats=$category->getPlats();
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'plats'=>$plats,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}

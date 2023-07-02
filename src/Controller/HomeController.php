<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ImagesRepository;
use App\Service\PaginationService;
class HomeController extends AbstractController
{
    private $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }
    // #[Route('/home', name: 'homepage')]
    // public function index(): Response
    // {
    //     return $this->render('home/index.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }
    #[Route('/home', name: 'homepage')]
    public function galerie(ImagesRepository $imagesRepository, Request $request): Response
    {
        $pageSize = 12;
        $results = $imagesRepository->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

        $pagination = $this->paginationService->paginate(
            $results,
            $request->query->getInt('page', 1),
            $pageSize
        );
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'images' => $imagesRepository->findAll(),
            'pagination' => $pagination,
        ]);
    }
}

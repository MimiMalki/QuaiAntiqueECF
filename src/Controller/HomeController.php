<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ImagesRepository;
class HomeController extends AbstractController
{
    #[Route('/home', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/home', name: 'homepage')]
    public function galerie(ImagesRepository $imagesRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }
    // public function index(ManagerRegistry $doctrine,PlatRepository $platRepository): Response
    // {

    //      // Entity Manager de Symfony
    //     $entityManager = $doctrine->getManager();
    //     $platRepository = $entityManager->getRepository(Plat::class);
    //      // On récupère tous les articles disponibles en base de données
    //     $plats   = $platRepository->findAll();
    //     return $this->render('home/index.html.twig', [
    //         'controller_name' => 'HomeController',
    //         'plats'  => $plats
    //     ]);

    // }

}

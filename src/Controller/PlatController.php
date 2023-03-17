<?php

namespace App\Controller;

use App\Entity\Plat;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlatRepository;
#[Route('/plat')]
class PlatController extends AbstractController
{
    #[Route('/', name: 'app_plat_index', methods: ['GET'])]
    public function index(PlatRepository $platRepository): Response
    {
        return $this->render('plat/listePlat.html.twig', [
            'plats' => $platRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'app_plat_show', methods: ['GET'])]
    public function show(Plat $plat): Response
    {

        return $this->render('plat/viewPlat.html.twig', [
            'plat' => $plat

        ]);

    }
}

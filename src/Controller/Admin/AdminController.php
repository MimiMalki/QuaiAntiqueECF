<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SeuilMaximumRepository;
use App\Repository\ReservationRepository;
use App\Service\PaginationService;


class AdminController extends AbstractController
{
    private $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }
    #[Route('/admin', name: 'app_admin')]
    public function index(SeuilMaximumRepository $seuilMaximumRepository, ReservationRepository $reservationRepository, Request $request): Response
    {
        $pageSize = 6;
        $results = $reservationRepository->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();

        $pagination = $this->paginationService->paginate(
            $results,
            $request->query->getInt('page', 1),
            $pageSize
        );
        return $this->render('adminBase.html.twig', [
            'controller_name' => 'AdminController',
            'seuil_maximums' => $seuilMaximumRepository->findAll(),
            'reservations' => $reservationRepository->findAll(),
            'pagination' => $pagination,
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Plat;
use App\Form\Admin\PlatType;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PaginationService;


#[Route('/admin/plat')]
class AdminPlatController extends AbstractController
{
    private $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }
    #[Route('/', name: 'app_admin_plat_index', methods: ['GET'])]
    public function index(PlatRepository $platRepository, Request $request): Response
    {
        $pageSize = 5;
        $results = $platRepository->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();

        $pagination = $this->paginationService->paginate(
            $results,
            $request->query->getInt('page', 1),
            $pageSize
        );
        return $this->render('admin/admin_plat/index.html.twig', [
            'plats' => $platRepository->findAll(),
            'pagination' => $pagination,

        ]);
    }

    #[Route('/new', name: 'app_admin_plat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlatRepository $platRepository): Response
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $platRepository->save($plat, true);

            return $this->redirectToRoute('app_admin_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_plat/new.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_plat_show', methods: ['GET'])]
    public function show(Plat $plat): Response
    {
        return $this->render('admin/admin_plat/show.html.twig', [
            'plat' => $plat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_plat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plat $plat, PlatRepository $platRepository): Response
    {
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $platRepository->save($plat, true);

            return $this->redirectToRoute('app_admin_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_plat/edit.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_plat_delete', methods: ['POST'])]
    public function delete(Request $request, Plat $plat, PlatRepository $platRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->request->get('_token'))) {
            $platRepository->remove($plat, true);
        }

        return $this->redirectToRoute('admin/app_admin_plat_index', [], Response::HTTP_SEE_OTHER);
    }
}

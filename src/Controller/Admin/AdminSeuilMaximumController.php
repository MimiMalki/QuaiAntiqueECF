<?php

namespace App\Controller\Admin;

use App\Entity\SeuilMaximum;
use App\Form\Admin\SeuilMaximumType;
use App\Repository\SeuilMaximumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/seuil/maximum')]
class AdminSeuilMaximumController extends AbstractController
{
    #[Route('/', name: 'app_admin_seuil_maximum_index', methods: ['GET'])]
    public function index(SeuilMaximumRepository $seuilMaximumRepository): Response
    {
        return $this->render('admin/admin_seuil_maximum/index.html.twig', [
            'seuil_maximums' => $seuilMaximumRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_seuil_maximum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SeuilMaximumRepository $seuilMaximumRepository): Response
    {
        $seuilMaximum = new SeuilMaximum();
        $form = $this->createForm(SeuilMaximumType::class, $seuilMaximum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seuilMaximumRepository->save($seuilMaximum, true);

            return $this->redirectToRoute('app_admin_seuil_maximum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_seuil_maximum/new.html.twig', [
            'seuil_maximum' => $seuilMaximum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_seuil_maximum_show', methods: ['GET'])]
    public function show(SeuilMaximum $seuilMaximum): Response
    {
        return $this->render('admin/admin_seuil_maximum/show.html.twig', [
            'seuil_maximum' => $seuilMaximum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_seuil_maximum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SeuilMaximum $seuilMaximum, SeuilMaximumRepository $seuilMaximumRepository): Response
    {
        $form = $this->createForm(SeuilMaximumType::class, $seuilMaximum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seuilMaximumRepository->save($seuilMaximum, true);

            return $this->redirectToRoute('app_admin_seuil_maximum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_seuil_maximum/edit.html.twig', [
            'seuil_maximum' => $seuilMaximum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_seuil_maximum_delete', methods: ['POST'])]
    public function delete(Request $request, SeuilMaximum $seuilMaximum, SeuilMaximumRepository $seuilMaximumRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seuilMaximum->getId(), $request->request->get('_token'))) {
            $seuilMaximumRepository->remove($seuilMaximum, true);
        }

        return $this->redirectToRoute('app_admin_seuil_maximum_index', [], Response::HTTP_SEE_OTHER);
    }
}

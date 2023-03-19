<?php

namespace App\Controller\Admin;

use App\Entity\Allergie;
use App\Form\Admin\AllergieType;
use App\Repository\AllergieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/allergie')]
class AdminAllergieController extends AbstractController
{
    #[Route('/', name: 'app_allergie_index', methods: ['GET'])]
    public function index(AllergieRepository $allergieRepository): Response
    {
        return $this->render('admin/admin_allergie/index.html.twig', [
            'allergies' => $allergieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_allergie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AllergieRepository $allergieRepository): Response
    {
        $allergie = new Allergie();
        $form = $this->createForm(AllergieType::class, $allergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allergieRepository->save($allergie, true);

            return $this->redirectToRoute('app_allergie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_allergie/new.html.twig', [
            'allergie' => $allergie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_allergie_show', methods: ['GET'])]
    public function show(Allergie $allergie): Response
    {
        return $this->render('admin/admin_allergie/show.html.twig', [
            'allergie' => $allergie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_allergie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Allergie $allergie, AllergieRepository $allergieRepository): Response
    {
        $form = $this->createForm(AllergieType::class, $allergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allergieRepository->save($allergie, true);

            return $this->redirectToRoute('app_allergie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_allergie/edit.html.twig', [
            'allergie' => $allergie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_allergie_delete', methods: ['POST'])]
    public function delete(Request $request, Allergie $allergie, AllergieRepository $allergieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$allergie->getId(), $request->request->get('_token'))) {
            $allergieRepository->remove($allergie, true);
        }

        return $this->redirectToRoute('app_allergie_index', [], Response::HTTP_SEE_OTHER);
    }
}

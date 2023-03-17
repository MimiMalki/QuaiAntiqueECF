<?php

namespace App\Controller;

use App\Entity\Resevation;

use App\Form\ResevationType;
use App\Repository\ResevationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/resevation')]
class ResevationController extends AbstractController
{
    #[Route('/', name: 'app_resevation_index', methods: ['GET'])]
    public function index(ResevationRepository $resevationRepository): Response
    {
        return $this->render('resevation/index.html.twig', [
            'resevations' => $resevationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_resevation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ResevationRepository $resevationRepository): Response
    {

        $resevation = new Resevation();
        // Récupérer l'utilisateur connecté et ses allergies
        $user = $this->getUser();


        if ($user) {
            $nbr = $user->getNumbreOfPeople();
            $allergies = $user->getAllergies();
            $resevation->setUser($user);
            $resevation->setNumbreOfPeople($nbr);
            foreach ($allergies as $allergie) {
                $resevation->addAllergie($allergie);
            }
        }
        
        // $nbr = $user->getNumbreOfPeople();
        // $allergies = $user->getAllergies();
        // $resevation->setUser($user);
        // $resevation->setNumbreOfPeople($nbr);
        // foreach ($allergies as $allergie) {
        //     $resevation->addAllergie($allergie);
        // }

        // Récupérer les données du formulaire et les associer à la réservation
        $form = $this->createForm(ResevationType::class, $resevation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resevationRepository->save($resevation, true);

            return $this->redirectToRoute('app_resevation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resevation/new.html.twig', [
            'resevation' => $resevation,
            'form' => $form,
            // 'allergies' => $allergies,
        ]);
    }

    #[Route('/{id}', name: 'app_resevation_show', methods: ['GET'])]
    public function show(Resevation $resevation): Response
    {
        return $this->render('resevation/show.html.twig', [
            'resevation' => $resevation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_resevation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Resevation $resevation, ResevationRepository $resevationRepository): Response
    {
        $form = $this->createForm(ResevationType::class, $resevation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resevationRepository->save($resevation, true);

            return $this->redirectToRoute('app_resevation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resevation/edit.html.twig', [
            'resevation' => $resevation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_resevation_delete', methods: ['POST'])]
    public function delete(Request $request, Resevation $resevation, ResevationRepository $resevationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $resevation->getId(), $request->request->get('_token'))) {
            $resevationRepository->remove($resevation, true);
        }

        return $this->redirectToRoute('app_resevation_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\User;
use App\Form\Admin\ReservationType;
use App\Form\Admin\UserType;
use App\Repository\ReservationRepository;
use App\Entity\SeuilMaximum;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/admin/reservation')]
class AdminReservationController extends AbstractController
{
    private $paginationService;

    public function __construct(PaginationService $paginationService,EntityManagerInterface $entityManager)
    {
        $this->paginationService = $paginationService;
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_admin_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, Request $request): Response
    {
        $pageSize = 6;
        $results = $reservationRepository->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

        $pagination = $this->paginationService->paginate(
            $results,
            $request->query->getInt('page', 1),
            $pageSize
        );
        return $this->render('admin/admin_reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        // Récupérer l'utilisateur connecté et ses propreity
        $user = $this->getUser();
        if ($user) {
            $nom = $user->getFirstname();
            $prenom = $user->getLastname();
            $email = $user->getEmail();
            $nbr = $user->getNumbreOfPeople();
            $allergies = $user->getAllergies();

            $reservation->setFirstname($nom);
            $reservation->setLastname($prenom);
            $reservation->setEmail($email);
            // $reservation->setUser($user);
            $reservation->setNumbreOfPeople($nbr);
            foreach ($allergies as $allergie) {
                $reservation->addAllergie($allergie);
            }
            // Associer l'utilisateur à la réservation
            $reservation->setUser($user);
        }
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer la valeur du champ 'date' du formulaire
            $reservationDate = $form->get('date')->getData();

            // Récupérer les réservations existantes pour la date spécifiée
            $existingReservations = $reservationRepository->findBy([
                'date' => $reservationDate,
            ]);
            // Calculer la somme des nombres de personnes pour les réservations existantes
            $totalReservedPeople = 0;
            foreach ($existingReservations as $existingReservation) {
                $totalReservedPeople += $existingReservation->getNumbreOfPeople();
            }
            // Récupérer le seuil maximal depuis la table seuilMax
            $seuilMax = $entityManager->getRepository(SeuilMaximum::class)->find(1);
            if ($seuilMax === null) {
                // Option 1 : Afficher un message d'erreur
                $errorMessage = "Le seuil maximal n'est pas défini. Veuillez contacter le restaurant pour plus d'informations.";
                $this->addFlash('error', $errorMessage);
            } else {
                // Comparaison de la somme des nombres de personnes réservées avec le seuil maximal
                if ($totalReservedPeople + $reservation->getNumbreOfPeople() <= $seuilMax->getNbrSeatMax()) {
                    // Il y a des places disponibles
                    $entityManager->persist($reservation);
                    $entityManager->flush();

                    if (!$this->getUser()) {
                        // Utilisateur non connecté
                        $this->addFlash('success', 'Votre réservation a été enregistrée avec succès. Veuillez vous connecter pour voir les détails.');
                    
                        // Redirection vers la page de connexion avec le paramètre reservationSuccess
                        return $this->redirectToRoute('app_login', ['reservationSuccess' => true]);
                    } else {
                        // Utilisateur connecté
                        $this->addFlash('success', 'Votre réservation a été enregistrée avec succès.');

                        return $this->redirectToRoute('app_admin_reservation_index', [], Response::HTTP_SEE_OTHER);
                    }
                } else {
                    // Afficher un message d'erreur au visiteur indiquant que le restaurant est complet pour cette date et cette heure
                    $errorMessage = "Désolé, le restaurant est complet, il n'y a malheuresement plus de places pour la date sélectionnées. Veuillez choisir une autre date .";
                    $this->addFlash('error', $errorMessage);
                    // Rediriger vers la même page pour afficher le flash message d'erreur
                    return $this->redirectToRoute('app_admin_reservation_new');
                }
            }
        }
        // Passer les données récupérées au template Twig
        return $this->render('admin/admin_reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    //     $reservation = new Reservation();
    //     $form = $this->createForm(ReservationType::class, $reservation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $reservationRepository->save($reservation, true);

    //         return $this->redirectToRoute('app_admin_reservation_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('admin/admin_reservation/new.html.twig', [
    //         'reservation' => $reservation,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_admin_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('admin/admin_reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
    if ($user) {
        $nom = $user->getFirstname();
        $prenom = $user->getLastname();
        $email = $user->getEmail();
        $nbr = $user->getNumbreOfPeople();
        $allergies = $user->getAllergies();

        // Vérifier si la variable $allergies est définie
        if (isset($allergies)) {
            foreach ($allergies as $allergie) {
                $reservation->addAllergie($allergie);
            }
        }
    }

    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer la valeur du champ 'date' du formulaire
        $reservationDate = $form->get('date')->getData();

        // Récupérer les réservations existantes pour la date spécifiée
        $existingReservations = $reservationRepository->findBy([
            'date' => $reservationDate,
        ]);
        // Calculer la somme des nombres de personnes pour les réservations existantes
        $totalReservedPeople = 0;
        foreach ($existingReservations as $existingReservation) {
            $totalReservedPeople += $existingReservation->getNumbreOfPeople();
        }
        // Récupérer le seuil maximal depuis la table seuilMax
        $seuilMax = $entityManager->getRepository(SeuilMaximum::class)->find(1);
        if ($seuilMax === null) {
            // Option 1 : Afficher un message d'erreur
            $errorMessage = "Le seuil maximal n'est pas défini. Veuillez contacter le restaurant pour plus d'informations.";
            $this->addFlash('error', $errorMessage);
        } else {
            // Comparaison de la somme des nombres de personnes réservées avec le seuil maximal
            if ($totalReservedPeople + $reservation->getNumbreOfPeople() <= $seuilMax->getNbrSeatMax()) {
                // Il y a des places disponibles
                $entityManager->persist($reservation);
                $entityManager->flush();

                if (!$this->getUser()) {
                    // Utilisateur non connecté
                    $this->addFlash('success', 'Votre réservation a été enregistrée avec succès. Veuillez vous connecter pour voir les détails.');
                
                    // Redirection vers la page de connexion avec le paramètre reservationSuccess
                    return $this->redirectToRoute('app_login', ['reservationSuccess' => true]);
                } else {
                    // Utilisateur connecté
                    $this->addFlash('success', 'Votre réservation a été enregistrée avec succès.');

                    return $this->redirectToRoute('app_admin_reservation_index', [], Response::HTTP_SEE_OTHER);
                }
            } else {
                // Afficher un message d'erreur au visiteur indiquant que le restaurant est complet pour cette date et cette heure
                $errorMessage = "Désolé, le restaurant est complet, il n'y a malheuresement plus de places pour la date sélectionnées. Veuillez choisir une autre date .";
                $this->addFlash('error', $errorMessage);
                // Rediriger vers la même page pour afficher le flash message d'erreur
                return $this->redirectToRoute('app_admin_reservation_new');
            }
        }
    }
    // Passer les données récupérées au template Twig
    return $this->renderForm('admin/admin_reservation/edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);
}
        // $form = $this->createForm(ReservationType::class, $reservation);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $reservationRepository->save($reservation, true);

        //     return $this->redirectToRoute('app_admin_reservation_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->render('admin/admin_reservation/edit.html.twig', [
        //     'reservation' => $reservation,
        //     'form' => $form,
        // ]);
    // }

    #[Route('/{id}', name: 'app_admin_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_admin_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}

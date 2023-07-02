<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\SeuilMaximum;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\SeuilMaximumRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    //récupérer l'utilisateur connecté et toutes les réservations associées à cet utilisateur
    public function afficherReservations(ManagerRegistry $doctrin, ReservationRepository $reservationRepository)
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser()) {
            throw new AccessDeniedException('Veuillez vous connecter ou créer un compte.');
        }
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer toutes les réservations associées à l'utilisateur connecté
        $entityManager = $doctrin->getManager();
        $reservationRepository = $entityManager->getRepository(Reservation::class);
        $reservations = $reservationRepository->findBy(['user' => $user]);

        // Passer les données récupérées au template Twig
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
        // // Récupérer l'utilisateur connecté
        // $user = $this->getUser();

        // // Récupérer toutes les réservations associées à l'utilisateur connecté
        // $reservations = $user->getReservations();

        // // Passer les données récupérées au template Twig
        // return $this->render('reservation/index.html.twig', [
        //     'reservations' => $reservations,
        // ]);
    }
    private $seuilMaximum = null;
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager, SeuilMaximumRepository $seuilMaximumRepository): Response
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
            // var_dump($allergies);
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

                        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
                    }
                } else {
                    // Afficher un message d'erreur au visiteur indiquant que le restaurant est complet pour cette date et cette heure
                    $errorMessage = "Désolé, le restaurant est complet, il n'y a malheuresement plus de places pour la date sélectionnées. Veuillez choisir une autre date .";
                    $this->addFlash('error', $errorMessage);
                    // Rediriger vers la même page pour afficher le flash message d'erreur
                    return $this->redirectToRoute('app_reservation_new');
                }
            }
        }
        // Passer les données récupérées au template Twig
        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager, SeuilMaximumRepository $seuilMaximumRepository): Response
{
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
        $reservation->setNumbreOfPeople($nbr);

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

                    return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
                }
            } else {
                // Afficher un message d'erreur au visiteur indiquant que le restaurant est complet pour cette date et cette heure
                $errorMessage = "Désolé, le restaurant est complet, il n'y a malheuresement plus de places pour la date sélectionnées. Veuillez choisir une autre date .";
                $this->addFlash('error', $errorMessage);
                // Rediriger vers la même page pour afficher le flash message d'erreur
                return $this->redirectToRoute('app_reservation_new');
            }
        }
    }
    // Passer les données récupérées au template Twig
    return $this->render('reservation/edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);
}

    //     public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    // {
    //     $form = $this->createForm(ReservationType::class, $reservation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Récupérer les nouvelles allergies sélectionnées par l'utilisateur
    //         $allergies = $form->get('allergie')->getData();

    //         // Supprimer toutes les allergies associées à la réservation existante
    //         foreach ($reservation->getAllergie() as $allergie) {
    //             $reservation->removeAllergie($allergie);
    //         }

    //         // Associer les nouvelles allergies à la réservation
    //         foreach ($allergies as $allergie) {
    //             $reservation->addAllergie($allergie);
    //         }

    //         $reservationRepository->save($reservation, true);

    //         return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('reservation/edit.html.twig', [
    //         'reservation' => $reservation,
    //         'form' => $form,
    //     ]);
    // }


    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}

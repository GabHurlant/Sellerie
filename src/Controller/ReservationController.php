<?php

// src/Controller/ReservationController.php

namespace App\Controller;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Equipment;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;

class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}/new', name: 'reservation_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Equipment $equipment, Request $request, EntityManagerInterface $em, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $reservation->setEquipment($equipment);
        $reservation->setUser($this->getUser());

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if startDate is before endDate
            if ($reservation->getStartDate() > $reservation->getEndDate()) {
                $this->addFlash('error', 'The start date must be before the end date.');
                return $this->redirectToRoute('reservation_new', ['id' => $equipment->getId()]);
            }
    
            // Check for overlapping reservations
            $existingReservations = $reservationRepository->findBy(['equipment' => $equipment]);
            foreach ($existingReservations as $existingReservation) {
                if (
                    ($reservation->getStartDate() >= $existingReservation->getStartDate() && $reservation->getStartDate() <= $existingReservation->getEndDate()) ||
                    ($reservation->getEndDate() >= $existingReservation->getStartDate() && $reservation->getEndDate() <= $existingReservation->getEndDate())
                ) {
                    $this->addFlash('error', 'These dates are already reserved.');
                    return $this->redirectToRoute('reservation_new', ['id' => $equipment->getId()]);
                }
            }
    
            $em->persist($reservation);
            $em->flush();
    
            $this->addFlash('success', 'Reservation created successfully!');
            return $this->redirectToRoute('user_reservations');
        }

        // Handle form validation errors
        foreach ($form->getErrors(true) as $error) {
            $this->addFlash('error', $error->getMessage());
        }

        $existingReservations = $reservationRepository->findBy(['equipment' => $equipment]);

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'equipment' => $equipment,
            'existingReservations' => $existingReservations,
        ]);
    }

    #[Route('/reservations', name: 'user_reservations')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $reservations = $entityManager->getRepository(Reservation::class)->findBy(['user' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/reservation/edit/{id}', name: 'reservation_edit')]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Save the changes
            
            $this->addFlash('success', 'Reservation updated successfully!');
            return $this->redirectToRoute('user_reservations');
        }

        // Handle form validation errors
        foreach ($form->getErrors(true) as $error) {
            $this->addFlash('error', $error->getMessage());
        }

        return $this->render('reservation/edit.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

    #[Route('/reservation/cancel/{id}', name: 'reservation_cancel')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function cancel(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        // Ensure that the user can only cancel their own reservations.
        if ($reservation->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'You cannot cancel this reservation.');
            return $this->redirectToRoute('user_reservations');
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Reservation canceled successfully.');
        return $this->redirectToRoute('user_reservations');
    }
}

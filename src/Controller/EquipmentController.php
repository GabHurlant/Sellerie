<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Repository\EquipmentRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipmentController extends AbstractController
{
    #[Route('/equipment', name: 'app_equipment')]
    public function index(EquipmentRepository $equipmentRepository): Response
    {

        $equipments = $equipmentRepository->findAll();

        return $this->render('equipment/index.html.twig', [
            'equipments' => $equipments,
        ]);
    }

    #[Route('/equipment/{id}', name: 'app_details_equipment')]
    public function details(Equipment $equipment, ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy(['equipment' => $equipment]);

        return $this->render('equipment/details.html.twig', [
            'equipment' => $equipment,
            'reservations' => $reservations,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Locations;
use App\Entity\Reservations;
use App\Service\ReservationServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReservationController extends MyAbstractController
{
    #[Route('/reservation', name: 'reservation')]
    public function index(EntityManagerInterface $db): Response
    {
        $locations = $db->getRepository(Locations::class)->findAll();
        return $this->isLoggedIn() ? $this->render('reservation/index.html.twig', ['locations' => $locations]) : $this->redirectToRoute('app_home');
    }

    #[Route('/reservation', name: 'reservationGET', methods: ['GET'])]
    public function getReservations(
        Request                    $request,
        EntityManagerInterface     $db,
        ReservationServiceProvider $reservationServiceProvider,
    ): Response
    {
        $requestParams = $request->query;

        $reservations = $db->getRepository(Reservations::class)->findBy([
            'date'=>\DateTime::createFromFormat('Y-m-d', $requestParams->get('date')),
            'id_location'=>$requestParams->get('id_location')
        ]);

        return new JsonResponse($reservationServiceProvider->prepareReservationForJson($reservations));
    }

    #[Route('/reservation', name: 'reservationPOST', methods: ['POST'])]
    public function setReservation(
        Request $request,
        EntityManagerInterface $db,
        ReservationServiceProvider $reservationServiceProvider,
    ): Response
    {
        $requestBody = $request->request;

        $location = $db->getRepository(Locations::class)->find(['id' => $requestBody->get('id_location')]);

        $user = $this->getUser();

        if (!$reservationServiceProvider->validate($request)) {
            return new JsonResponse($reservationServiceProvider->errorMessage);
        }

        $reservation = new Reservations();

        $reservation->setIdUser($user);
        $reservation->setDate(\DateTime::createFromFormat('Y-m-d', $requestBody->get('date_start')));
        $reservation->setIdLocation($location);

        $db->persist($reservation);
        $db->flush();

        return new JsonResponse(['state' => 'good']);
    }
}

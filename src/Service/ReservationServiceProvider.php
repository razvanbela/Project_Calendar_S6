<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Locations;
use App\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ReservationServiceProvider
{
    public string $errorMessage;

    public function __construct(
        protected Security               $security,
        protected EntityManagerInterface $db
    )
    {
    }

    function prepareReservationForJson(array $reservations): array
    {
        $loggedUserId = $this->security->getUser()->getUserIdentifier();

        $data = [];
        foreach ($reservations as $reservation) {
            $data[] = [
                'id' => $reservation->getId(),
                'name' => $reservation->getUser()->getName(),
                'date' => $reservation->getDate(),
                'location_address' => $reservation->getLocation()->getAddress(),
                'location_city' => $reservation->getLocation()->getCity(),
                "isReservationFromLoggedInUser" => $reservation->getUser()->getId() === $loggedUserId,
            ];
        }
        return $data;
    }

    public function validate(Request $request): bool
    {
        $requestBody = $request->request;

        $locationEntity = $this->db->getRepository(Locations::class);

        $reservationEntity = $this->db->getRepository(Reservations::class);

        if (date('Y-m-d') <= date('Y-m-d', strtotime($requestBody->get('date')))) {

            $user = $this->security->getUser();

            $location = $locationEntity->findOneBy(['id' => $requestBody->get('id_location')]);

            $maxCapacity = $location->getCapacity();

            $reservationMadeByThisUser = $reservationEntity->count([
                'user' => $user,
                'date' => \DateTime::createFromFormat('Y-m-d', $requestBody->get('date')),
            ]);

            $numberOfReservationsOnThisDay = $reservationEntity->count([
                'date' => \DateTime::createFromFormat('Y-m-d', $requestBody->get('date')),
                'location' => $location,
            ]);

            $availableCapacity=$maxCapacity-$numberOfReservationsOnThisDay;

            if($reservationMadeByThisUser > 0){
                $this->errorMessage = 'You already have a reservation on ' . $requestBody->get('date');
                return false;
            }else if ($availableCapacity <= 0){
                $this->errorMessage = 'The capacity of this location ( '. $maxCapacity. ') has been reached';
                return false;
            }
            return true;
        }else
            $this->errorMessage = 'We couldn\'t process this request';
        return false;
    }
}

<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use Illuminate\Http\Request;
use App\Services\Mobile\ReservationService;
use App\Traits\ResponseTrait;

class ReservationController extends Controller
{
    use ResponseTrait;


    public function __construct(protected ReservationService $reservationService)
    {
    }

    public function indexReceived(Request $request)
    {
        try {
            $reservations = $this->reservationService->getReceivedReservations($request);
            $reservationsResource = ReservationResource::collection($reservations)->map(function ($item) {
                return collect($item)->except(['user', 'advertisement']);
            });

            return $this->showResponse($reservationsResource, 'Received reservations retrieved successfully');
        } catch (\Exception $e) {
            return $this->showError($e, 'Failed to retrieve received reservations');
        }
    }

    public function indexSended(Request $request)
    {
        try {
            $reservations = $this->reservationService->getSendedReservations($request);
            $reservationsResource = ReservationResource::collection($reservations)->map(function ($item) {
                return collect($item)->except(['user', 'advertisement']);
            });

            return $this->showResponse($reservationsResource, 'Sent reservations retrieved successfully');
        } catch (\Exception $e) {
            return $this->showError($e, 'Failed to retrieve sent reservations');
        }
    }

    public function show($id)
    {
        try {
            $reservation = $this->reservationService->getReservation($id);
            return $this->showResponse(new ReservationResource($reservation), 'Reservation details retrieved successfully');
        } catch (\Exception $e) {
            return $this->showError($e, 'Failed to retrieve reservation');
        }
    }

    public function store(Request $request)
    {
        try {
            $reservation = $this->reservationService->createReservation($request);
            return $this->showResponse(new ReservationResource($reservation), 'Reservation created successfully');
        } catch (\Exception $e) {
            return $this->showError($e, 'Failed to create reservation');
        }
    }

    public function accept($id)
    {
        try {
            $reservation = $this->reservationService->acceptReservation($id);
            return $this->showResponse($reservation, 'Reservation accepted successfully');
        } catch (\Exception $e) {
            return $this->showError($e, 'Failed to accept reservation');
        }
    }

    public function reject($id)
    {
        try {
            $reservation = $this->reservationService->rejectReservation($id);
            return $this->showResponse($reservation, 'Reservation rejected successfully');
        } catch (\Exception $e) {
            return $this->showError($e, 'Failed to reject reservation');
        }
    }
}

<?php

namespace App\Services\Mobile;

use App\Filters\Mobile\ReservationFilter;
use App\Models\Advertisement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ReservationService.
 */
class ReservationService
{

    public function __construct(protected ReservationFilter $filter)
    {
    }

    public function getReceivedReservations(Request $request)
    {
        $array = request()->user()->ads()->pluck("id")->toArray();
        $reservation = Reservation::query()->whereIn("advertisement_id", $array);
        return $this->filter->apply($reservation)->get();
    }

    public function getSendedReservations(Request $request)
    {
        $reservation = request()->user()->reservations()->getQuery();
        return $this->filter->apply($reservation)->get();
    }

    public function getReservation($reservation_id)
    {
        return Reservation::with('reservationDates', 'user', 'advertisement')->findOrFail($reservation_id);
    }


    public function createReservation(Request $request)
    {
        $request->validate([
            'advertisement_id' => 'required|exists:advertisements,id',
            'dates' => 'array',
            'dates.*' => 'date'
        ]);

        $advertisement = Advertisement::findOrFail($request->advertisement_id);
        if ($advertisement->status != 'active') {
            throw new \Exception('Sorry, this advertisement is out of date', Response::HTTP_BAD_REQUEST);
        }
        return DB::transaction(function () use ($advertisement, $request) {
            $reservation = Reservation::create([
                'user_id' => auth()->user()->id,
                'advertisement_id' => $advertisement->id,
                'status' => 'pending'
            ]);
            foreach ($request->dates as $date) {
                $reservation->reservationDates()->create([
                    'date' => $date
                ]);
            }
            return $reservation->load('reservationDates');
        });

    }

    public function acceptReservation($reservation_id)
    {
        $array = request()->user()->ads()->pluck("id")->toArray();
        $reservation = Reservation::findOrFail($reservation_id);
        if (!in_array($reservation->advertisement_id, $array)) {
            throw new \Exception('You can not change this reservation status', Response::HTTP_UNAUTHORIZED);
        }
        $reservation = Reservation::findOrFail($reservation_id);
        if ($reservation->status != 'pending') {
            throw new \Exception('This reservation already processed', Response::HTTP_BAD_REQUEST);
        }
        $reservation->status = 'accepted';
        $reservation->save();
        return $reservation;
    }


    public function rejectReservation($reservation_id)
    {
        $array = request()->user()->ads()->pluck("id")->toArray();
        $reservation = Reservation::findOrFail($reservation_id);
        if (!in_array($reservation->advertisement_id, $array)) {
            throw new \Exception('You can not change this reservation status', Response::HTTP_UNAUTHORIZED);
        }
        if ($reservation->status != 'pending') {
            throw new \Exception('This reservation already processed', Response::HTTP_BAD_REQUEST);
        }
        $reservation->status = 'reject';
        $reservation->save();
        return $reservation;

    }
}

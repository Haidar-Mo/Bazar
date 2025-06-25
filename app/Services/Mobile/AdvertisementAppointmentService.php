<?php

namespace App\Services\Mobile;

use App\Models\Advertisement;
use App\Models\AdvertisementAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class AdvertisementAppointmentService.
 */
class AdvertisementAppointmentService
{

    public function index()
    {
        return auth()->user()
            ->appointment()->with(['user', 'advertisement'])
            ->latest()->get();
    }

    public function create(Request $request, string $id)
    {
        $user = auth()->user();
        $advertisement = Advertisement::where('status', '=', 'active')->findOrFail($id);
        if ($user->id == $advertisement->user_id)
            throw new \Exception("لا يمكنك حجز موعد مع نفسك", 400);

        $data = $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string'
        ]);

        return DB::transaction(function () use ($advertisement, $user, $data) {
            return $advertisement->appointment()->create([
                'user_id' => $user->id,
                'user_owner_id' => $advertisement->user_id,
                'date' => $data['date'],
                'note' => $data['note'],
                'status' => 'pending'
            ]);
        });
    }

    public function acceptAppointment(string $appointmentId)
    {
        $appointment = auth()->user()->appointment()
            ->findOrFail($appointmentId);
        if ($appointment->status != 'pending')
            throw new \Exception("this appointment is already processed", 400);
        return DB::transaction(function () use ($appointment) {

            $appointment->status = 'accepted';
            $appointment->save();
            return $appointment;
        });

    }

    public function rejectAppointment(string $appointmentId)
    {
        $appointment = auth()->user()->appointment()
            ->findOrFail($appointmentId);
        if ($appointment->status != 'pending')
            throw new \Exception("this appointment is already processed", 400);
        return DB::transaction(function () use ($appointment) {

            $appointment->status = 'rejected';
            $appointment->save();
            return $appointment;
        });

    }

    public function delete(string $appointmentId)
    {
        $appointment = auth()->user()->appointment()
            ->findOrFail($appointmentId);
             $appointment->delete();
    }


}

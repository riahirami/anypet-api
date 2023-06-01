<?php

namespace App\Repositories;

use App\Models\Ad;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationNotification;
use App\Notifications\RespondOnReservationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationRepository
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ad_id' => 'required|exists:ads,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable',
            'reservation_date' => 'nullable',
        ]);

        $sender = auth()->user();
        $advertisement = Ad::findOrFail($validatedData['ad_id']);
        $receiver = User::findOrFail($validatedData['receiver_id']);

        $reservation = Reservation::create([
            'ad_id' => $advertisement->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $validatedData['message'],
            'reservation_date' => $validatedData['reservation_date'],
            'status' => 'pending',
        ]);
        if ($reservation) {
            $reservation->receiver->notify(new ReservationNotification($reservation));
        }
        return $reservation;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function responseOnReservation(Request $request)
    {

        $reservation = Reservation::findOrFail($request->id);
            $reservation->status = $request->status;

        if ($request->status == '2') {
            $reservation->confirmed_at = Carbon::now();
        }
        $reservation->save();
        if ($reservation) {
            $reservation->receiver->notify(new RespondOnReservationNotification($reservation));
        }
        return $reservation;

    }

    /**
     * @return array
     */
    public function getAuthenticatedUserReservations()
    {
        $user = auth()->user();
        $reservationsSended = $user->sentReservations()->with('advertisement','receiver')->get();
        $reservationsReceived = $user->receivedReservations()->with('advertisement','sender')->get();

        return ['send' => $reservationsSended, 'received' => $reservationsReceived];
    }

    /**
     * @param $adId
     * @return mixed
     */
    public function getAdReservations($adId)
    {
        $ad = Ad::findOrFail($adId);
        $reservations = $ad->reservations;
        return $reservations;
    }
}

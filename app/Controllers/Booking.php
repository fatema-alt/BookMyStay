<?php

namespace App\Controllers;

use App\Models\RoomModel;

class Booking extends BaseController
{
    public function create($roomId)
    {
        $roomModel = new RoomModel();

        $room = $roomModel
            ->select('rooms.*, room_types.name as room_type, room_types.description, room_types.image, room_types.max_adults, room_types.max_children')
            ->join('room_types', 'room_types.id = rooms.room_type_id')
            ->where('rooms.id', $roomId)
            ->first();

        if (!$room) {
            return redirect()->to(site_url('room/search'));
        }

        return view('booking/create', [
            'room' => $room
        ]);
    }
    public function store()
{
    $roomId = $this->request->getPost('room_id');
    $checkIn = $this->request->getPost('check_in');
    $checkOut = $this->request->getPost('check_out');
    $adults = $this->request->getPost('adults');
    $children = $this->request->getPost('children');
    $special = $this->request->getPost('special_request');

    // basic validation
    if (empty($checkIn) || empty($checkOut)) {
        return redirect()->back()->with('error', 'Select dates');
    }

    $db = \Config\Database::connect();

    // 🔥 Availability check AGAIN (important)
    $existing = $db->query("
        SELECT id FROM bookings
        WHERE room_id = ?
        AND status IN ('pending','approved','confirmed')
        AND check_in < ?
        AND check_out > ?
    ", [$roomId, $checkOut, $checkIn])->getResult();

    if (!empty($existing)) {
        return redirect()->back()->with('error', 'Room already booked for these dates');
    }

    // get room price
    $room = $db->table('rooms')->where('id', $roomId)->get()->getRow();

    // calculate nights
    $nights = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);

    // total amount
    $total = $nights * $room->price;

    // booking code
    $code = 'BMS-' . rand(1000,9999);

    // insert
    $db->table('bookings')->insert([
        'booking_code' => $code,
        'user_id' => session()->get('user_id'), // temporary
        'room_id' => $roomId,
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'total_nights' => $nights,
        'adults' => $adults,
        'children' => $children,
        'total_amount' => $total,
        'status' => 'pending',
        'special_request' => $special
    ]);

    return redirect()->to(site_url('booking/success/' . $code));
}
public function success($code)
{
    $db = \Config\Database::connect();

    $booking = $db->table('bookings')
        ->where('booking_code', $code)
        ->get()
        ->getRow();

    if (!$booking) {
        return redirect()->to('/');
    }

    return view('booking/success', [
        'booking' => $booking
    ]);
}
}
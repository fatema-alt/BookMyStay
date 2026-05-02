<?php

namespace App\Controllers;

class Review extends BaseController
{
    public function create($bookingId)
    {
        $db = \Config\Database::connect();

        $booking = $db->table('bookings')
            ->select('bookings.*, rooms.room_number, rooms.room_type_id, room_types.name as room_type')
            ->join('rooms', 'rooms.id = bookings.room_id')
            ->join('room_types', 'room_types.id = rooms.room_type_id')
            ->where('bookings.id', $bookingId)
            ->where('bookings.user_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if (!$booking) {
            return redirect()->to(site_url('my-bookings'))->with('error', 'Booking not found.');
        }

        return view('review/create', [
            'booking' => $booking
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $bookingId = $this->request->getPost('booking_id');
        $roomTypeId = $this->request->getPost('room_type_id');

        $existing = $db->table('reviews')
            ->where('booking_id', $bookingId)
            ->where('user_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if ($existing) {
            return redirect()->to(site_url('my-bookings'))->with('error', 'You already reviewed this booking.');
        }

        $db->table('reviews')->insert([
            'user_id' => session()->get('user_id'),
            'room_type_id' => $roomTypeId,
            'booking_id' => $bookingId,
            'rating' => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment'),
            'status' => 'pending'
        ]);

        return redirect()->to(site_url('my-bookings'))->with('success', 'Review submitted for approval.');
    }
}
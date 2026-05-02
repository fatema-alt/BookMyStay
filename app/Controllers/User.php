<?php

namespace App\Controllers;

class User extends BaseController
{
    public function bookings()
{
    $db = \Config\Database::connect();

    $bookings = $db->table('bookings')
        ->select('bookings.*, rooms.room_number, room_types.name as room_type, payments.status as payment_status')
        ->join('rooms', 'rooms.id = bookings.room_id')
        ->join('room_types', 'room_types.id = rooms.room_type_id')
        ->join('payments', 'payments.booking_id = bookings.id', 'left')
        ->where('bookings.user_id', session()->get('user_id'))
        ->orderBy('bookings.id', 'DESC')
        ->get()
        ->getResultArray();

    return view('user/bookings', [
        'bookings' => $bookings
    ]);
}
    public function cancelBooking($id)
{
    $db = \Config\Database::connect();

    $booking = $db->table('bookings')
        ->where('id', $id)
        ->get()
        ->getRowArray();

    // booking cancel
    $db->table('bookings')
        ->where('id', $id)
        ->update(['status' => 'cancelled']);

    // 🔔 notification
    $db->table('notifications')->insert([
        'user_id' => $booking['user_id'],
        'title' => 'Booking Cancelled',
        'message' => 'Your booking ' . $booking['booking_code'] . ' has been cancelled.',
        'link' => 'my-bookings/view/' . $booking['id']
    ]);

    return redirect()->back()->with('success', 'Booking cancelled.');
}
    public function bookingDetails($id)
{
    $db = \Config\Database::connect();

    $booking = $db->table('bookings')
        ->select('
            bookings.*,
            rooms.room_number,
            rooms.floor,
            room_types.name as room_type,
            room_types.description,
            room_types.image,
            payments.status as payment_status,
            payments.transaction_id,
            payments.payment_method,
            payments.paid_at
        ')
        ->join('rooms', 'rooms.id = bookings.room_id')
        ->join('room_types', 'room_types.id = rooms.room_type_id')
        ->join('payments', 'payments.booking_id = bookings.id', 'left')
        ->where('bookings.id', $id)
        ->where('bookings.user_id', session()->get('user_id'))
        ->get()
        ->getRowArray();

    if (!$booking) {
        return redirect()->to(site_url('my-bookings'))->with('error', 'Booking not found.');
    }

    return view('user/booking_details', [
        'booking' => $booking
    ]);
}
public function profile()
{
    $db = \Config\Database::connect();

    $user = $db->table('users')
        ->where('id', session()->get('user_id'))
        ->get()
        ->getRowArray();

    return view('user/profile', [
        'user' => $user
    ]);
}

public function updateProfile()
{
    $db = \Config\Database::connect();
    $userId = session()->get('user_id');

    $data = [
        'name' => $this->request->getPost('name'),
        'phone' => $this->request->getPost('phone'),
    ];

    $image = $this->request->getFile('profile_image');

    if ($image && $image->isValid() && !$image->hasMoved()) {

        $newName = $image->getRandomName();

        $image->move(FCPATH . 'assets/images/users', $newName);

        $data['profile_image'] = $newName;

        session()->set('profile_image', $newName);
    }

    $db->table('users')
        ->where('id', $userId)
        ->update($data);

    session()->set('user_name', $this->request->getPost('name'));

    return redirect()->back()->with('success', 'Profile updated successfully.');
}
public function readNotification($id)
{
    $db = \Config\Database::connect();

    $notification = $db->table('notifications')
        ->where('id', $id)
        ->where('user_id', session()->get('user_id'))
        ->get()
        ->getRowArray();

    if ($notification) {
        $db->table('notifications')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        if (!empty($notification['link'])) {
            return redirect()->to(site_url($notification['link']));
        }
    }

    return redirect()->to(site_url('my-bookings'));
}
}
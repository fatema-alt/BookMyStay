<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function dashboard()
    {
        $db = \Config\Database::connect();

        $data['totalBookings'] = $db->table('bookings')->countAllResults();

        $data['confirmedBookings'] = $db->table('bookings')
            ->where('status', 'confirmed')
            ->countAllResults();

        $data['pendingBookings'] = $db->table('bookings')
            ->where('status', 'pending')
            ->countAllResults();

        $data['cancelledBookings'] = $db->table('bookings')
            ->where('status', 'cancelled')
            ->countAllResults();

        $data['totalCustomers'] = $db->table('users')
            ->where('role', 'customer')
            ->countAllResults();

        $revenue = $db->table('payments')
            ->selectSum('amount')
            ->where('status', 'paid')
            ->get()
            ->getRow();

        $data['totalRevenue'] = $revenue->amount ?? 0;

        $monthlyRevenue = $db->table('payments')
            ->select("MONTH(paid_at) as month, SUM(amount) as revenue")
            ->where('status', 'paid')
            ->where('paid_at IS NOT NULL')
            ->groupBy("MONTH(paid_at)")
            ->orderBy("MONTH(paid_at)", "ASC")
            ->get()
            ->getResultArray();

        $months = [];
        $revenues = [];

        foreach ($monthlyRevenue as $row) {
            $months[] = date("M", mktime(0, 0, 0, $row['month'], 1));
            $revenues[] = (float) $row['revenue'];
        }

        $data['months'] = json_encode($months);
        $data['revenues'] = json_encode($revenues);

        return view('admin/dashboard', $data);
    }
    public function bookings()
    {
        $db = \Config\Database::connect();

        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');

        $builder = $db->table('bookings b');
        $builder->select('
        b.*,
        u.name as customer_name,
        u.email,
        r.room_number,
        rt.name as room_type,
        p.status as payment_status
    ');
        $builder->join('users u', 'u.id = b.user_id');
        $builder->join('rooms r', 'r.id = b.room_id');
        $builder->join('room_types rt', 'rt.id = r.room_type_id');
        $builder->join('payments p', 'p.booking_id = b.id', 'left');

        if (!empty($status) && $status !== 'all') {
            $builder->where('b.status', $status);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('b.booking_code', $search)
                ->orLike('u.name', $search)
                ->orLike('u.email', $search)
                ->orLike('r.room_number', $search)
                ->orLike('rt.name', $search)
                ->groupEnd();
        }

        $builder->orderBy('b.id', 'DESC');

        return view('admin/bookings', [
            'bookings' => $builder->get()->getResultArray(),
            'activeStatus' => $status ?? 'all',
            'search' => $search ?? ''
        ]);
    }
    public function approveBooking($id)
    {
        $db = \Config\Database::connect();

        $booking = $db->table('bookings')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found.');
        }

        $db->table('bookings')
            ->where('id', $id)
            ->update(['status' => 'confirmed']);

        $db->table('notifications')->insert([
            'user_id' => $booking['user_id'],
            'title' => 'Booking Confirmed',
            'message' => 'Your booking ' . $booking['booking_code'] . ' has been confirmed.',
        ]);
        $db->table('notifications')->insert([
            'user_id' => $booking['user_id'],
            'title' => 'Booking Confirmed',
            'message' => 'Your booking ' . $booking['booking_code'] . ' has been confirmed.',
            'link' => 'my-bookings/view/' . $booking['id']
        ]);

        return redirect()->to(site_url('admin/bookings'))->with('success', 'Booking approved successfully.');
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
    public function roomTypes()
    {
        $db = \Config\Database::connect();

        $roomTypes = $db->table('room_types')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/room_types/index', [
            'roomTypes' => $roomTypes
        ]);
    }

    public function createRoomType()
    {
        return view('admin/room_types/create');
    }

    public function storeRoomType()
{
    $db = \Config\Database::connect();

    $imageName = null;
    $image = $this->request->getFile('image');

    if ($image && $image->isValid() && !$image->hasMoved()) {
        $imageName = $image->getRandomName();
        $image->move(FCPATH . 'assets/images/rooms', $imageName);
    }

    $db->table('room_types')->insert([
        'name' => $this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
        'base_price' => $this->request->getPost('base_price'),
        'max_adults' => $this->request->getPost('max_adults'),
        'max_children' => $this->request->getPost('max_children'),
        'image' => $imageName,
        'status' => $this->request->getPost('status'),
    ]);

    return redirect()->to(site_url('admin/room-types'))->with('success', 'Room type added successfully.');
}
    public function editRoomType($id)
    {
        $db = \Config\Database::connect();

        $roomType = $db->table('room_types')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$roomType) {
            return redirect()->to(site_url('admin/room-types'));
        }

        return view('admin/room_types/edit', [
            'roomType' => $roomType
        ]);
    }

   public function updateRoomType($id)
{
    $db = \Config\Database::connect();

    $roomType = $db->table('room_types')
        ->where('id', $id)
        ->get()
        ->getRowArray();

    $imageName = $roomType['image'] ?? null;
    $image = $this->request->getFile('image');

    if ($image && $image->isValid() && !$image->hasMoved()) {
        $imageName = $image->getRandomName();
        $image->move(FCPATH . 'assets/images/rooms', $imageName);
    }

    $db->table('room_types')
        ->where('id', $id)
        ->update([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'base_price' => $this->request->getPost('base_price'),
            'max_adults' => $this->request->getPost('max_adults'),
            'max_children' => $this->request->getPost('max_children'),
            'image' => $imageName,
            'status' => $this->request->getPost('status'),
        ]);

    return redirect()->to(site_url('admin/room-types'))->with('success', 'Room type updated successfully.');
}

    public function deleteRoomType($id)
    {
        $db = \Config\Database::connect();

        $db->table('room_types')->where('id', $id)->delete();

        return redirect()->to(site_url('admin/room-types'))->with('success', 'Room type deleted successfully.');
    }
    public function rooms()
    {
        $db = \Config\Database::connect();

        $rooms = $db->table('rooms')
            ->select('rooms.*, room_types.name as room_type')
            ->join('room_types', 'room_types.id = rooms.room_type_id')
            ->orderBy('rooms.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/rooms/index', ['rooms' => $rooms]);
    }

    public function createRoom()
    {
        $db = \Config\Database::connect();

        $roomTypes = $db->table('room_types')
            ->where('status', 'active')
            ->get()
            ->getResultArray();

        return view('admin/rooms/create', ['roomTypes' => $roomTypes]);
    }

    public function storeRoom()
    {
        $db = \Config\Database::connect();

        $imageName = null;
        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'assets/images/rooms', $imageName);
        }

        $db->table('rooms')->insert([
            'room_type_id' => $this->request->getPost('room_type_id'),
            'room_number' => $this->request->getPost('room_number'),
            'floor' => $this->request->getPost('floor'),
            'price' => $this->request->getPost('price'),
            'image' => $imageName,
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to(site_url('admin/rooms'))->with('success', 'Room added successfully.');
    }
    public function editRoom($id)
    {
        $db = \Config\Database::connect();

        $room = $db->table('rooms')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        $roomTypes = $db->table('room_types')
            ->where('status', 'active')
            ->get()
            ->getResultArray();

        if (!$room) {
            return redirect()->to(site_url('admin/rooms'));
        }

        return view('admin/rooms/edit', [
            'room' => $room,
            'roomTypes' => $roomTypes
        ]);
    }

    public function updateRoom($id)
    {
        $db = \Config\Database::connect();

        $room = $db->table('rooms')->where('id', $id)->get()->getRowArray();

        $imageName = $room['image'] ?? null;
        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'assets/images/rooms', $imageName);
        }

        $db->table('rooms')
            ->where('id', $id)
            ->update([
                'room_type_id' => $this->request->getPost('room_type_id'),
                'room_number' => $this->request->getPost('room_number'),
                'floor' => $this->request->getPost('floor'),
                'price' => $this->request->getPost('price'),
                'image' => $imageName,
                'status' => $this->request->getPost('status'),
            ]);

        return redirect()->to(site_url('admin/rooms'))->with('success', 'Room updated successfully.');
    }
    public function deleteRoom($id)
    {
        $db = \Config\Database::connect();

        $db->table('rooms')->where('id', $id)->delete();

        return redirect()->to(site_url('admin/rooms'))->with('success', 'Room deleted successfully.');
    }

    public function reviews()
    {
        $db = \Config\Database::connect();

        $reviews = $db->table('reviews')
            ->select('reviews.*, users.name as user_name, room_types.name as room_type')
            ->join('users', 'users.id = reviews.user_id')
            ->join('room_types', 'room_types.id = reviews.room_type_id')
            ->orderBy('reviews.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/reviews', [
            'reviews' => $reviews
        ]);
    }
    public function approveReview($id)
    {
        $db = \Config\Database::connect();

        $review = $db->table('reviews')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        // approve
        $db->table('reviews')
            ->where('id', $id)
            ->update(['status' => 'approved']);

        // 🔔 notification
        $db->table('notifications')->insert([
            'user_id' => $review['user_id'],
            'title' => 'Review Approved',
            'message' => 'Your review has been approved.',
            'link' => 'my-bookings'
        ]);

        return redirect()->back()->with('success', 'Review approved.');
    }
    public function deleteReview($id)
    {
        $db = \Config\Database::connect();

        $db->table('reviews')
            ->where('id', $id)
            ->delete();

        return redirect()->to(site_url('admin/reviews'))->with('success', 'Review deleted successfully.');
    }
    public function payments()
    {
        $db = \Config\Database::connect();

        $payments = $db->table('payments p')
            ->select('
            p.*,
            b.booking_code,
            u.name as customer_name,
            u.email
        ')
            ->join('bookings b', 'b.id = p.booking_id')
            ->join('users u', 'u.id = p.user_id')
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/payments', [
            'payments' => $payments
        ]);
    }
}
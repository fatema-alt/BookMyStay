<?php

namespace App\Controllers;

use App\Models\RoomModel;

class Room extends BaseController
{
    public function search()
    {
        $checkIn = $this->request->getGet('check_in');
        $checkOut = $this->request->getGet('check_out');

        $rooms = [];

        if (!empty($checkIn) && !empty($checkOut)) {
            $roomModel = new RoomModel();
            $rooms = $roomModel->getAvailableRooms($checkIn, $checkOut);
        }

        return view('rooms/search', [
            'rooms' => $rooms,
            'check_in' => $checkIn,
            'check_out' => $checkOut
        ]);
    }
}
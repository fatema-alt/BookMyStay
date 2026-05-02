<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $reviews = $db->table('reviews')
            ->select('reviews.*, users.name as user_name, room_types.name as room_type')
            ->join('users', 'users.id = reviews.user_id')
            ->join('room_types', 'room_types.id = reviews.room_type_id')
            ->where('reviews.status', 'approved')
            ->orderBy('reviews.id', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        return view('home', [
            'reviews' => $reviews
        ]);
    }
}
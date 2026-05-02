<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';

    public function getAvailableRooms($checkIn, $checkOut)
{
    $sql = "
        SELECT 
            r.id,
            r.room_number,
            r.floor,
            r.price,
            rt.name AS room_type,
            rt.description,
            rt.image,
            rt.max_adults,
            rt.max_children,
            COALESCE(AVG(rv.rating), 0) AS avg_rating,
            COUNT(rv.id) AS review_count
        FROM rooms r
        JOIN room_types rt ON rt.id = r.room_type_id
        LEFT JOIN reviews rv 
            ON rv.room_type_id = rt.id 
            AND rv.status = 'approved'
        WHERE r.status = 'available'
        AND rt.status = 'active'
        AND r.id NOT IN (
            SELECT b.room_id
            FROM bookings b
            WHERE b.status IN ('pending', 'approved', 'confirmed')
            AND b.check_in < ?
            AND b.check_out > ?
        )
        GROUP BY r.id
    ";

    return $this->db->query($sql, [$checkOut, $checkIn])->getResultArray();
}
    
}
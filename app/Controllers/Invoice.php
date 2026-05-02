<?php

namespace App\Controllers;

use Dompdf\Dompdf;

class Invoice extends BaseController
{
    public function download($bookingId)
    {
        $db = \Config\Database::connect();

        $booking = $db->table('bookings')
            ->select('
                bookings.*,
                users.name as customer_name,
                users.email,
                users.phone,
                rooms.room_number,
                room_types.name as room_type,
                payments.status as payment_status,
                payments.transaction_id,
                payments.payment_method,
                payments.paid_at
            ')
            ->join('users', 'users.id = bookings.user_id')
            ->join('rooms', 'rooms.id = bookings.room_id')
            ->join('room_types', 'room_types.id = rooms.room_type_id')
            ->join('payments', 'payments.booking_id = bookings.id', 'left')
            ->where('bookings.id', $bookingId)
            ->where('bookings.user_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if (!$booking) {
            return redirect()->to(site_url('my-bookings'))->with('error', 'Booking not found.');
        }

        if ($booking['payment_status'] !== 'paid') {
            return redirect()->back()->with('error', 'Invoice available only after payment.');
        }

        $html = view('invoice/pdf', ['booking' => $booking]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('invoice-' . $booking['booking_code'] . '.pdf', [
            'Attachment' => true
        ]);
    }
}
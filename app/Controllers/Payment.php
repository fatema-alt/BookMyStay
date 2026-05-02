<?php

namespace App\Controllers;

class Payment extends BaseController
{
    private function validatePayment($valId)
    {
        $url = rtrim(getenv('SSLCZ_BASE_URL'), '/') . '/validator/api/validationserverAPI.php';

        $query = http_build_query([
            'val_id' => $valId,
            'store_id' => getenv('SSLCZ_STORE_ID'),
            'store_passwd' => getenv('SSLCZ_STORE_PASSWORD'),
            'v' => 1,
            'format' => 'json',
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $content = curl_exec($ch);
        curl_close($ch);

        return json_decode($content, true);
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        echo '<hr>';

        echo '<pre>';
        print_r($content);
        echo '</pre>';
        exit;
    }

    public function pay($bookingId)
    {
        $db = \Config\Database::connect();

        $booking = $db->table('bookings')
            ->select('bookings.*, users.name, users.email, users.phone')
            ->join('users', 'users.id = bookings.user_id')
            ->where('bookings.id', $bookingId)
            ->where('bookings.user_id', session()->get('user_id'))
            ->get()
            ->getRowArray();

        if (!$booking) {
            return redirect()->to(site_url('my-bookings'))->with('error', 'Booking not found.');
        }

        if ($booking['status'] === 'cancelled' || $booking['status'] === 'cancel_requested') {
            return redirect()->to(site_url('my-bookings'))->with('error', 'This booking cannot be paid.');
        }

        $paid = $db->table('payments')
            ->where('booking_id', $bookingId)
            ->where('status', 'paid')
            ->get()
            ->getRowArray();

        if ($paid) {
            return redirect()->to(site_url('my-bookings'))->with('success', 'This booking is already paid.');
        }

        $postData = [
            'store_id' => getenv('SSLCZ_STORE_ID'),
            'store_passwd' => getenv('SSLCZ_STORE_PASSWORD'),
            'total_amount' => $booking['total_amount'],
            'currency' => 'BDT',
            'tran_id' => $booking['booking_code'],

            'success_url' => site_url('payment/success'),
            'fail_url' => site_url('payment/fail'),
            'cancel_url' => site_url('payment/cancel'),
            'ipn_url' => site_url('payment/ipn'),

            'cus_name' => $booking['name'],
            'cus_email' => $booking['email'],
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $booking['phone'] ?: '01700000000',

            'shipping_method' => 'NO',
            'product_name' => 'Hotel Room Booking',
            'product_category' => 'Hotel',
            'product_profile' => 'general',
        ];

        $url = rtrim(getenv('SSLCZ_BASE_URL'), '/') . '/gwprocess/v4/api.php';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $content = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($content, true);

        if (!empty($response['GatewayPageURL'])) {
            return redirect()->to($response['GatewayPageURL']);
        }

        return redirect()->to(site_url('my-bookings'))->with('error', 'Payment gateway connection failed.');
    }

    public function success()
    {
        $valId = $this->request->getPost('val_id');

        if (!$valId) {
            return redirect()->to(site_url('my-bookings'))->with('error', 'Invalid payment response.');
        }

        $validation = $this->validatePayment($valId);

        if (
            !$validation ||
            !isset($validation['status']) ||
            !in_array($validation['status'], ['VALID', 'VALIDATED'])
        ) {
            return redirect()->to(site_url('my-bookings'))->with('error', 'Payment validation failed.');
        }

        $db = \Config\Database::connect();

        $tranId = $validation['tran_id'];
        $amount = $validation['amount'];
        $bankTranId = $validation['bank_tran_id'] ?? null;

        $booking = $db->table('bookings')
            ->where('booking_code', $tranId)
            ->get()
            ->getRowArray();

        if (!$booking) {
            return redirect()->to(site_url('my-bookings'))->with('error', 'Booking not found.');
        }

        if ((float) $booking['total_amount'] != (float) $amount) {
            return redirect()->to(site_url('my-bookings'))->with('error', 'Payment amount mismatch.');
        }

        $existingPayment = $db->table('payments')
            ->where('booking_id', $booking['id'])
            ->where('status', 'paid')
            ->get()
            ->getRowArray();

        if (!$existingPayment) {
            $db->table('payments')->insert([
                'booking_id' => $booking['id'],
                'user_id' => $booking['user_id'],
                'payment_method' => 'sslcommerz',
                'transaction_id' => $bankTranId,
                'amount' => $amount,
                'status' => 'paid',
                'paid_at' => date('Y-m-d H:i:s'),
            ]);

            $db->table('bookings')
                ->where('id', $booking['id'])
                ->update(['status' => 'confirmed']);
            $user = $db->table('users')
                ->where('id', $booking['user_id'])
                ->get()
                ->getRowArray();

            if ($user) {
                session()->set([
                    'user_id' => $user['id'],
                    'user_name' => $user['name'],
                    'profile_image' => $user['profile_image'] ?? null,
                    'is_logged_in' => true
                ]);
            }

            $db->table('notifications')->insert([
                'user_id' => $booking['user_id'],
                'title' => 'Payment Successful',
                'message' => 'Payment for booking ' . $booking['booking_code'] . ' was successful.',
                'link' => 'my-bookings/view/' . $booking['id'],
            ]);
        }

        return redirect()->to(site_url('my-bookings/view/' . $booking['id']))->with('success', 'Payment successful.');
    }

    public function fail()
    {
        return redirect()->to(site_url('my-bookings'))->with('error', 'Payment failed.');
    }

    public function cancel()
    {
        return redirect()->to(site_url('my-bookings'))->with('error', 'Payment cancelled.');
    }

    public function ipn()
    {
        $valId = $this->request->getPost('val_id');

        if (!$valId) {
            return $this->response->setStatusCode(400)->setBody('Invalid IPN');
        }

        $validation = $this->validatePayment($valId);

        if (!$validation || !isset($validation['status']) || !in_array($validation['status'], ['VALID', 'VALIDATED'])) {
            return $this->response->setStatusCode(400)->setBody('Validation failed');
        }

        return $this->response->setStatusCode(200)->setBody('IPN received');
    }
}
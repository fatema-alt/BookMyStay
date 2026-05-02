<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            background: #f5f5f5;
            margin: 0;
        }

        .invoice-box {
            padding: 35px;
            border: 3px solid #D4AF37;
            background: #ffffff;
        }

        .header {
            background: #111827;
            color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border-bottom: 5px solid #D4AF37;
        }

        .brand {
            font-size: 34px;
            color: #D4AF37;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0;
        }

        .tagline {
            color: #d1d5db;
            margin-top: 6px;
            font-size: 13px;
        }

        .invoice-title {
            text-align: right;
            margin-top: -65px;
        }

        .invoice-title h2 {
            color: #D4AF37;
            font-size: 28px;
            margin: 0;
        }

        .invoice-title p {
            margin: 6px 0;
            font-size: 12px;
            color: #e5e7eb;
        }

        .invoice-box,
        .section,
        .total-box,
        .footer {
            page-break-inside: avoid;
        }

        .section {
            margin-top: 18px;
        }

        .section h3 {
            color: #111827;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 8px;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .info-grid td {
            padding: 8px 0;
            font-size: 13px;
        }

        .label {
            color: #6b7280;
            width: 160px;
        }

        .value {
            font-weight: bold;
            color: #111827;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.items th {
            background: #111827;
            color: #D4AF37;
            padding: 10px;
            font-size: 12px;
            text-align: left;
        }

        table.items td {
            border-bottom: 1px solid #e5e7eb;
            padding: 10px;
            font-size: 12px;
        }

        .total-box {
            margin-top: 28px;
            text-align: right;


        }

        .total {
            display: inline-block;
            background: #111827;
            color: #D4AF37;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
        }

        .paid {
            color: #065f46;
            background: #d1fae5;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .thank-you {
            color: #D4AF37;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        @page {
            margin: 20px;
        }
    </style>
</head>

<body>

    <div class="invoice-box">

        <div class="header">
            <h1 class="brand">BookMyStay</h1>
            <p class="tagline">Luxury Hotel Booking Experience</p>

            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p>Booking Code: <?= esc($booking['booking_code']) ?></p>
                <p>Date: <?= date('Y-m-d') ?></p>
            </div>
        </div>

        <div class="section">
            <h3>Customer Information</h3>
            <table class="info-grid">
                <tr>
                    <td class="label">Customer Name</td>
                    <td class="value"><?= esc($booking['customer_name']) ?></td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value"><?= esc($booking['email']) ?></td>
                </tr>
                <tr>
                    <td class="label">Phone</td>
                    <td class="value"><?= esc($booking['phone']) ?></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Booking Information</h3>

            <table class="items">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Nights</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><?= esc($booking['room_type']) ?> - Room <?= esc($booking['room_number']) ?></td>
                        <td><?= esc($booking['check_in']) ?></td>
                        <td><?= esc($booking['check_out']) ?></td>
                        <td><?= esc($booking['total_nights']) ?></td>
                        <td>BDT <?= esc($booking['total_amount']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Payment Details</h3>
            <table class="info-grid">
                <tr>
                    <td class="label">Payment Status</td>
                    <td><span class="paid">PAID</span></td>
                </tr>
                <tr>
                    <td class="label">Payment Method</td>
                    <td class="value"><?= esc(strtoupper($booking['payment_method'])) ?></td>
                </tr>
                <tr>
                    <td class="label">Transaction ID</td>
                    <td class="value"><?= esc($booking['transaction_id']) ?></td>
                </tr>
                <tr>
                    <td class="label">Paid At</td>
                    <td class="value"><?= esc($booking['paid_at']) ?></td>
                </tr>
            </table>
        </div>

        <div class="total-box">
            <div class="total">
                Total Paid: BDT <?= esc($booking['total_amount']) ?>
            </div>
        </div>

        <div class="footer">
            <div class="thank-you">Thank you for choosing BookMyStay</div>
            <div>This invoice was generated electronically and does not require a signature.</div>
        </div>

    </div>

</body>

</html>
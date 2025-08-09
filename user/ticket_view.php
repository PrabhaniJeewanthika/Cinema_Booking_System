<?php
require_once(__DIR__ . '/../models/Booking.php');

if (isset($_GET['booking_id'])) {
    $model = new Booking();
        $booking = $model->getFullReceiptDetails((int)$_GET['booking_id']);

        if (!$booking) {
            die("Booking not found.");
        }
} else {
    echo "Booking ID is missing.";
}
        
// $booking comes from the controller
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
        .btn:hover { background-color: #0056b3; }
        .center { text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Cinema Booking Receipt</h2>
    <table>
        <tr><td><b>Booking ID</b></td><td><?= $booking['booking_id'] ?></td></tr>
        <tr><td><b>Customer Name</b></td><td><?= $booking['user_name'] ?></td></tr>
        <tr><td><b>Email</b></td><td><?= $booking['user_email'] ?></td></tr>
        <tr><td><b>Movie</b></td><td><?= $booking['movie_title'] ?></td></tr>
        <tr><td><b>Description</b></td><td><?= $booking['movie_description'] ?></td></tr>
        <tr><td><b>Duration</b></td><td><?= $booking['movie_duration'] ?> mins</td></tr>
        <tr><td><b>Seats</b></td><td><?= $booking['seats'] ?></td></tr>
        <tr><td><b>Booking Date</b></td><td><?= $booking['booking_date'] ?></td></tr>
        <tr><td><b>Payment Method</b></td><td><?= ucfirst($booking['payment_method']) ?></td></tr>
        <tr><td><b>Payment Status</b></td><td><?= ucfirst($booking['payment_status']) ?></td></tr>
        <tr><td><b>Payment Date</b></td><td><?= $booking['payment_date'] ?></td></tr>
    </table>

    <div class="center">
        <a href="generate_ticket.php?booking_id=<?= $booking['booking_id'] ?>" class="btn" target="_blank">
            Download PDF
        </a>
    </div>
</div>
</body>
</html>

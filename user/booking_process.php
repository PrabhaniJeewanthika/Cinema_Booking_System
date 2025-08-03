<?php
session_start();
require_once(__DIR__ . '/../controllers/BookingController.php');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = $_POST['movie_id'];
    $selectedSeats = isset($_POST['selected_seats']) ? explode(',', $_POST['selected_seats']) : [];

    if (empty($selectedSeats)) {
        die("No seats selected.");
    }

    $controller = new BookingController();
    $success = $controller->processBooking($user_id, $movie_id, $selectedSeats);

    if ($success) {
        echo "<script>alert('Booking Successful!'); window.location.href='payment_method.php';</script>";
    } else {
        echo "<script>alert('Booking Failed!'); window.history.back();</script>";
    }
} else {
    header("Location: select_seats.php");
}
?>

<?php
require_once(__DIR__ . '/../models/Booking.php');

class BookingController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new Booking();
    }

    // Fetch already booked seats for given movie
    public function fetchBookedSeats($movie_id) {
        return $this->bookingModel->getBookedSeats($movie_id);
    }

    // Process Booking Seats
    public function processBooking($user_id, $movie_id, $selectedSeats) {
        return $this->bookingModel->bookSeats($user_id, $movie_id, $selectedSeats);
    }
}
?>

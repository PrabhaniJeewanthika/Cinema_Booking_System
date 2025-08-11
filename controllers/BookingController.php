<?php
require_once(__DIR__ . '/../models/Booking.php');

class BookingController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new Booking();
    }

    // Fetch all booked seats for a movie
    public function fetchBookedSeats($movie_id) {
        return $this->bookingModel->getBookedSeats($movie_id);
    }

    // Process booking for selected seats
    public function processBooking($user_id, $movie_id, $selectedSeats) {
        return $this->bookingModel->bookSeats($user_id, $movie_id, $selectedSeats);
    }
}
?>

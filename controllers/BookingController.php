<?php
require_once(__DIR__ . '/../models/Booking.php');

class BookingController {

    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new Booking();
    }

    public function fetchBookedSeats($movie_id) {
        return $this->bookingModel->getBookedSeats($movie_id);
    }

    public function processBooking($user_id, $movie_id, $seats) {
        return $this->bookingModel->bookSeats($user_id, $movie_id, $seats);
    }
}
?>

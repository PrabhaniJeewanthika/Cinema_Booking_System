<?php
require_once(__DIR__ . '/../config/db.php');

class Booking {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function getBookedSeats($movie_id) {
        $stmt = $this->conn->prepare("SELECT seats FROM bookings WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookedSeats = [];
        while ($row = $result->fetch_assoc()) {
            $seatsArray = explode(',', $row['seats']);
            $bookedSeats = array_merge($bookedSeats, $seatsArray);
        }
        return $bookedSeats;
    }

    public function bookSeats($user_id, $movie_id, $seats) {
        $seatsStr = implode(",", $seats);

        $stmt = $this->conn->prepare("INSERT INTO bookings (user_id, movie_id, seats) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $movie_id, $seatsStr);

        return $stmt->execute();
    }
    
    public function getFullReceiptDetails($booking_id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT 
                b.id AS booking_id,
                u.name AS user_name,
                u.email AS user_email,
                m.title AS movie_title,
                m.description AS movie_description,
                m.duration AS movie_duration,
                b.seats,
                b.booking_date,
                p.payment_method,
                p.payment_status,
                p.payment_date
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN movies m ON b.movie_id = m.id
            LEFT JOIN payments p ON b.id = p.booking_id
            WHERE b.id = ?
        ");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        $stmt->close();
        return $booking;
    }
}
?>

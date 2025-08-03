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
}
?>

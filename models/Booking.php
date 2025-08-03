<?php
require_once(__DIR__ . '/../config/db.php');

class Booking {

    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function bookSeats($user_id, $movie_id, $seats) {
        $seats_str = implode(",", $seats);

        $stmt = $this->conn->prepare("INSERT INTO bookings (user_id, movie_id, seats) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("iis", $user_id, $movie_id, $seats_str);

        if ($stmt->execute()) {
            return true;
        } else {
            die("Execute failed: " . $stmt->error);
        }
    }

    public function getBookedSeats($movie_id) {
        $stmt = $this->conn->prepare("SELECT seats FROM bookings WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookedSeats = [];
        while ($row = $result->fetch_assoc()) {
            $seats = explode(",", $row['seats']);
            $bookedSeats = array_merge($bookedSeats, $seats);
        }

        return $bookedSeats;
    }
}
?>

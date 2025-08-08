<?php
require_once(__DIR__ . '/../config/db.php');

class Movie {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function getMovieById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getAllMovies() {
        $result = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
